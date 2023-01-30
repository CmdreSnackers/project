<?php
//if not not logged in redirect back to login

if(!Authentication::isLoggedIn()) {
  header('Location: /login');
  exit;
}

require dirname(__DIR__) . '/parts/header.php';
?>


    <div class="container mx-auto my-5" style="max-width: 800px;">
      <h1 class="h1 mb-4 text-center">Dashboard</h1>
      <div class="row">
        <?php if(Authentication::whoCanAccess('seller')): ?>
        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-pencil-square" style="font-size: 3rem;"></i>
                </div>
                Manage Products
              </h5>
              <div class="text-center mt-3">
                <a href="/manage-product" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div>
        <?php endif;?>

        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-chat" style="font-size: 3rem;"></i>
                </div>
                Messages
              </h5>
              <div class="text-center mt-3">
                <a href="/messages" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div>

    

        <!-- manage manage-users -->
        <?php if(Authentication::whoCanAccess('admin')): ?>
        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-people" style="font-size: 3rem;"></i>
                </div>
                Manage Users
              </h5>
              <div class="text-center mt-3">
                <a href="/admin" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div> <!-- end manage-users -->
        <?php endif;?>

      </div> <!-- row -->
      <div class="mt-4 text-center">
        <a href="/" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back</a
        >
      </div>
    </div>

<?php
require dirname(__DIR__) . '/parts/footer.php';
?>
