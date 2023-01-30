<?php
if(!Authentication::whoCanAccess('seller')) {
  header('Location: /login');
  exit;
}

$product = Products::productById($_GET['id']);

CSRF::generateToken('edit_product_form');

if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    $rules = [
      'name' => 'required',
      'price' => 'required',
      'content' => 'required',
      'image_url' => 'required',
      'csrf_token' => 'edit_product_form_csrf_token'
    ];

    $error = FormValidation::validate(
      $_POST,
      $rules
    );


    if ( !$error ) {

      Products::updateProduct(
        $product['id'], 
        $_POST['name'], 
        $_POST['price'],
        $_POST['content'],
        $_POST['image_url'],
      );

      CSRF::removeToken( 'edit_product_form' );

      header("Location: /manage-product");
      exit;

    }
}

require dirname(__DIR__) . '/parts/header.php';

?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit Product</h1>
      </div>
      <div class="card mb-2 p-4">
        <?php require dirname( __DIR__ ) . '/parts/error-box.php'; ?>
        <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
          <div class="mb-3">
            <label for="post-title" class="form-label">Name</label>
            <input
              type="text"
              class="form-control"
              id="post-name"
              value="<?php echo $product['name']; ?>"
              name="name"
            />
          </div>
          <div class="mb-3">
            <label for="post-title" class="form-label">Price</label>
            <input
              type="text"
              class="form-control"
              id="post-price"
              value="<?php echo $product['price']; ?>"
              name="price"
            />
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Content</label>
            <textarea class="form-control" id="post-content" name="content" rows="10"><?php echo $product['content']; ?></textarea>
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Image Url</label>
            <textarea class="form-control" id="post-image_url" name="image_url" rows="10"><?php echo $product['image_url']; ?></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo CSRF::getToken( 'edit_product_form' ); ?>"/>
        </form>
      </div>
      
      <div class="text-center">
        <a href="/manage-product" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Products</a
        >
      </div>
    </div>

<?php
require dirname(__DIR__) . '/parts/footer.php';
?>
