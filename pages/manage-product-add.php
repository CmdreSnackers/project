<?php
if ( !Authentication::whoCanAccess('user') ) {
  header( 'Location: /login' );
  exit;
}

CSRF::generateToken( 'add_product_form' );

if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

  $rules = [
    'name' => 'required',
    'price' => 'required',
    'content' => 'required',
    'image_url' => 'required',
    'csrf_token' => 'add_product_form_csrf_token',
  ];

  $error = FormValidation::validate(
    $_POST,
    $rules
  );

  if ( !$error ) {
    Products::createProduct(
      $_POST['name'],
      $_POST['price'],     
      $_POST['content'],
      $_POST['image_url'],
      $_SESSION['user']['id']
    );

    CSRF::removeToken( 'add_product_form' );

    header("Location: /manage-product");
    exit;

  } 
} 

require dirname(__DIR__) . '/parts/header.php';
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New Product</h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
          <div class="mb-3">
            <label for="post-name" class="form-label">name</label>
            <input type="text" class="form-control" id="post-name" name="name" />
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Price</label>
            <input type="text" class="form-control" id="post-content" name="content" />
          </div>
          <div class="mb-3">
            <label for="post-price" class="form-label">Content</label>
            <textarea class="form-control" id="post-price" rows="10 "name="price"></textarea>
          </div>
          <div class="mb-3">
            <label for="post-Image_url" class="form-label">Image Url</label>
            <textarea
              class="form-control" id="post-image_url" rows="10 "name="image_url"></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo CSRF::getToken("add_product_form"); ?>" />
        </form>
      </div>

      <div class="text-center">
        <a href="/manage-product" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back Products</a
        >
      </div>
    </div>

<?php
require dirname(__DIR__) . '/parts/footer.php';
?>
