<?php
$products = new Products();
$productlist = Products::getAllProducts(isset($_GET['id']));

 

require dirname(__DIR__) . '/parts/header.php';
?>
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-success">
      <div class="container-fluid">
        <a class="navbar-brand text-warning" href="/">Tenner</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link text-white" href="/dashboard">Dashboard</a>
            <a class="nav-link text-white" href="/orders">My Orders</a>
            <a class="nav-link text-white" href="/cart">Cart</a>
          </div>
        </div>
      </div>
    </nav>






    <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
      <div class="min-vh-100">



        <!-- products  -->
        <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php foreach($productlist as $product): ?>
          <div class="col">
            <div class="card h-100 border-0">
              <img src="<?php echo $product['image_url']; ?>" class="card-img" alt="pic of service listing">
              <div class="card-body text-center">
                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                <p class="card-text">$ <?php echo $product['price']; ?></p>

                

                <a href="/product?id=<?php echo $product['id']; ?>" class="btn btn-primary">View</a>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
        <!-- products end -->
      </div>

      <!-- footer -->
      <div class="d-flex justify-content-between align-items-center pt-4 pb-2">
        <div class="text-muted small">
          <a href="/" class="text-muted">For Educational Purposes Only</a>
        </div>
        <div class="d-flex align-items-center gap-3">
        <?php if(Authentication::isLoggedIn()): ?>
          <a href="/logout" class="btn btn-light btn-sm">Logout</a>
        <?php else: ?>
          <a href="/login" class="btn btn-light btn-sm">Login</a>
          <a href="/signup" class="btn btn-light btn-sm">Sign Up</a>
        <?php endif; ?>
        </div>
      </div>
      <!-- footer end -->
    </div>

<?php
require dirname(__DIR__) . '/parts/footer.php';
?>

