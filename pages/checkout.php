<?php
    if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

        if ( empty( $_SESSION['cart'] ) ) {
            $error = "Your cart is empty.";
        } 

        if (!Authentication::isLoggedIn()) {
            $error = "You must be logged in to checkout";
        }

        if ( !isset( $error ) ) {
            

            $bill_url = Products::createNewOrder(
                $_SESSION['user']['id'], 
                Cart::total(), 
                $_SESSION['cart'] 
            );


        Cart::emptyCart();

            if ( isset( $bill_url ) && !empty( $bill_url ) ) {
                header( 'Location: ' . $bill_url );
                exit;
            } else {
                $error = 'missing bill url';
            }


        }

    }

require dirname(__DIR__) . '/parts/header.php';
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php if ( isset( $error ) ): ?>
                <div class="alert alert-danger mb-3">
                    <?php echo $error; ?>
                </div>
            <?php else:?>
                <div class="alert alert-danger mb-3">
                    Something went wrong
                </div>
            <?php endif; ?>
            <a href="/cart" class="btn btn-primary">Back to cart</a>
        </div>
    </div>
</div>



<?php
require dirname(__DIR__) . '/parts/footer.php';
?>