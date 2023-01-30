<?php

class Products
{


    public static function getAllProducts($product_id)
    {
        if(Authentication::isUser()) {
            return DB::connect()->select(
                'SELECT * FROM products WHERE product_id = :id ORDER BY id DESC',
                [
                    'product_id' => $product_id
                ],
                true
            );
        }

        return DB::connect()->select(
            'SELECT * FROM products ORDER BY id DESC',
            [],
            true
        );
    }

    public static function productById($product_id)
    {
        return DB::connect()->select(
            'SELECT * FROM products WHERE id = :id',
            [
                'id' => $product_id
            ]
        );
    }



    public static function createProduct($name, $price, $content, $image_url, $user_id)
    {
        return DB::connect()->insert(
            'INSERT INTO products (name, price, content, image_url, user_id)
            VALUES (:name,:price, :content,:image_url, :user_id)',
            [
                'name' => $name,
                'price' => $price,
                'content' => $content,
                'image_url' => $image_url,
                'user_id' => $user_id,
            ]
        );
    }

    public static function deleteProduct($product_id)
    {
        return DB::connect()->delete(
            'DELETE FROM products where id = :id',
            [
                'id' => $product_id
            ]
        );
    }

    public static function updateProduct($id, $name,$price , $content, $image_url )
    {
        return DB::connect()->update(
            'UPDATE products SET 
            name = :name,price = :price , content = :content, image_url = :image_url 
            WHERE id = :id',
            [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'content' => $content,
                'image_url' => $image_url
            ]
        );
    }

    public static function callAPI( $api_url = '', $method = 'GET', $formdata = [], $headers = [] ) {

        $curl = curl_init();
    

        $curl_props = [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_CUSTOMREQUEST => $method
        ];

        if ( !empty( $formdata ) ) {
            $curl_props[ CURLOPT_POSTFIELDS ] = json_encode( $formdata );
        }

        if ( !empty( $headers ) ) {
            $curl_props[ CURLOPT_HTTPHEADER ] = $headers;
        }

        curl_setopt_array( $curl, $curl_props );

        $response = curl_exec( $curl );

        $error = curl_error( $curl );

        curl_close( $curl );
    
        if ( $error )
            return 'API not working';
    
        return json_decode( $response );
    }



    public static function createNewOrder(
        $user_id, $total_amount = 0, $products_in_cart = [])
    {
        return DB::connect()->insert('INSERT INTO orders (user_id, total_amount, transaction_id)
        VALUES (:user_id, :total_amount, :transaction_id)',
        [
            'user_id' => $user_id,
            'total_amount' => $total_amount,
            'transaction_id' => ''
        ]
        );




        foreach($products_in_cart as $product_id => $quantity)
        {

            return DB::connect()->insert(
                'INSERT INTO orders_products (order_id, product_id, quantity)
                VALUES (:order_id, :product_id, :quantity)',
                [
                    'order_id' => $order_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity
                ]
            );
        }


        $bill_url = '';


        $response = self::callAPI(
            BILLPLZ_API_URL . 'v3/bills', 
            'POST',
            [
                'collection_id' => BILLPLZ_COLLECTION_ID,
                'email' => $_SESSION['user']['email'],
                'name' => $_SESSION['user']['email'],
                'amount' => $total_amount * 100,
                'callback_url' => 'http://project.local/payment-callback',
                'description' => 'Order #' . $order_id, 
                'redirect_url' => 'http://project.local/payment-verification'
            ],
            [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode(BILLPLZ_API_KEY . ':')
            ]
        );

        if(isset($response->id)) {
            return DB::connect()->update(
                'UPDATE orders SET transaction_id = :transaction_id
                WHERE id = :order_id',
                [
                    'transaction_id' => $response->id,
                    'order_id' => $order_id
                ]
            );

        }

        if(isset($response->url)) {
            $bill_url = $response->url;
        }

        return $bill_url;
    }

    public static function updateOrder($transaction_id, $status)
    {
        return DB::connect()->update(
            'UPDATE orders SET status = :status WHERE transaction_id = :transaction_id',
            [
                'status' => $status,
                'transaction_id' => $transaction_id
            ]
        );

    }

    public static function listOrders($user_id)
    {
        return DB::connect()->select(
            'SELECT * FROM orders WHERE user_id = :user_id
            ORDER BY id DESC',
            [
                'user_id' => $user_id 
            ]
        );
    }

    public static function listProductsOrder($order_id)
    {

        return DB::connect()->select(
            'SELECT
            products.id,
            products.name,
            orders_products.order_id,
            orders_products.quantity
            FROM orders_products
            JOIN products
            ON orders_products.product_id = products.id
            WHERE order_id = :order_id',
            [
                'order_id' => $order_id
            ]
        );
    }
}

class Cart{
    
    public static function listAllProductsinCart()
    {


        $list = [];

        if(isset($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $product_id => $quantity) {

                $product = Products::productById($product_id);

                $list[] = [
                    'id' => $product_id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'total' => $product['price'] * $quantity,
                    'quantity' => $quantity
                ];
            }
        } 
        return $list;
    }

    public static function total()
    {
        $cart_total = 0;

        $list = self::listAllProductsinCart();

        foreach($list as $product) {
            $cart_total += $product['total'];
        }


        return $cart_total;
    }

    public static function add( $product_id )
    {
        if ( isset( $_SESSION['cart'] ) ) {
           $cart = $_SESSION['cart']; 
        } else {
            $cart = [];
        }


        if(isset($cart[$product_id]))
        {

            $cart[$product_id] += 1;
        } else {
            $cart[ $product_id ] = 1;
        }
        
        $_SESSION['cart'] = $cart;
    }



    public static function removeProductFromCart($product_id)
    {
        if(isset($_SESSION['cart'][$product_id])) {
            //unset it
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public static function emptyCart()
    {
        unset($_SESSION['cart']);
    }

}