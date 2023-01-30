<?php

if ( $_SERVER["REQUEST_METHOD"] == 'POST' ) {
 
    if(isset($_POST['action']) && $_POST['action'] =='remove' )
    {
        return Cart::removeProductFromCart($_POST['product_id']);
    } else {
        
        if ( isset( $_POST['product_id'] ) ) 
        {

            return Cart::add($_POST['product_id']);
        }
    }
}

require dirname(__DIR__) . '/parts/header.php';
?>

        <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
            
            <div class="min-vh-100">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h1">My Cart</h1>
                </div>
    

                <table class="table table-hover table-bordered table-striped table-light">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty(Cart::listAllProductsinCart())):?>
                        <tr>
                            <td colspan="5">
                                Cart is empty.
                            </td>
                        </tr>
                    <?php else:?>
                    <?php foreach(Cart::listAllProductsinCart() as $product): ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td>$<?php echo $product['price']; ?></td>
                            <td><?php echo $product['quantity'];?></td>
                            <td><?php echo $product['total']; ?></td>
                            <td>
                                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">

                                    <input type="hidden" name="action" value="remove">

                                    <input type="hidden" name="product_id" value="<?php echo $product['id'];?>">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;?>
                        <tr>
                            <td colspan="3" class="text-end">Total</td>
                            <td>$<?php echo Cart::total();?></td>
                            <td></td>
                        </tr>
                    <?php endif;?> 
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center my-3">
                    <a href="/" class="btn btn-light btn-sm">Continue Shopping</a>

                    <?php if(!empty(Cart::listAllProductsinCart())): ?>
                        <form action="/checkout" method="POST">
                            <input type="hidden" name="" value="">
                            <button class="btn btn-primary">Checkout</a>
                        </form>
                    <?php endif;?>
                </div>

            </div>

        </div>

        <div class="d-flex justify-content-between align-items-center pt-4 pb-2">
            <div class="text-muted small">
                <a href="/" class="text-muted">For Educational Purposes Only</a>
            </div>
        </div>
        
<?php
require dirname(__DIR__) . '/parts/footer.php';
?>