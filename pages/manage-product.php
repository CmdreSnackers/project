<?php

if ( !Authentication::whoCanAccess('seller') ) {
    header( 'Location: /login' );
    exit;
}

CSRF::generateToken( 'delete_product_form' );


if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $error = FormValidation::validate(
        $_POST,
        [
        'product_id' => 'required',
        'csrf_token' => 'delete_product_form_csrf_token'
        ]
    );

    if(!$error) {

        Products::deleteProduct($_POST['product_id']);

        CSRF::removeToken('delete_product_form');

        header('Location: /manage-product');
        exit;
    }
}

require dirname(__DIR__) . '/parts/header.php';
?>

<div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Products</h1>
        <div class="text-end">
            <a href="/manage-product-add" class="btn btn-primary btn-sm">Add New Product</a>
        </div>
    </div>
    <div class="card mb-2 p-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col" style="width: 40%;">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( Products::getAllProducts( $_SESSION['user']['id'] ) as $product ) : ?>
                <tr>
                    <th scope="row"><?php echo $product['id']; ?></th>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td class="text-end">
                        <div class="buttons">
                            <a href="/manage-product-edit?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-sm me-2"><i class="bi bi-pencil"></i></a>   
                                <button 
                                type="button" 
                                class="btn btn-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#product-<?php echo $product['id']; ?>">
                                <i class="bi bi-trash"></i>
                                </button>
                                <div class="modal fade" id="product-<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Product</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            Delete this product? (<?php echo $product['name']; ?>)
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <form
                                            method="POST"
                                            action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
                                            >
                                            <input 
                                                type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
                                            <input 
                                                type="hidden" name="csrf_token" value="<?php echo CSRF::getToken( 'delete_product_form' ); ?>"/>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                      </div>
                                  </div>
                                </div>                  
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <a href="/dashboard" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<?php 
require dirname(__DIR__) . '/parts/footer.php';
?>