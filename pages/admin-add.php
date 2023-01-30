<?php

 if ( !Authentication::whoCanAccess('admin') ) {
    header('Location: /dashboard');
    exit;
}
CSRF::generateToken( 'add_admin_form' );


if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $rules = [
        'name' => 'required',
        'email' => 'email_check',
        'role' => 'required',
        'csrf_token' => 'add_admin_form_csrf_token',
        'password' => 'password_check',
        'confirm_password' => 'is_password_match'
    ];

    $error = FormValidation::validate(
      $_POST,
      $rules
    );

    if(FormValidation::checkEmailUniqueness($_POST['email'])) {
    $error .= FormValidation::checkEmailUniqueness($_POST['email']);
    }

    if(!$error) {

    User::add(
      $_POST['name'], 
      $_POST['email'], 
      $_POST['role'], 
      $_POST['password']
    );

    CSRF::removeToken('add_admin_form');

    header("Location: /admin");
    exit;
    }
}

require dirname(__DIR__) . '/parts/header.php';
?>
<div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New Seller</h1>
      </div>
      <div class="card mb-2 p-4">
      <?php require dirname( __DIR__ ) . '/parts/error-box.php'; ?>
        <form
          method="POST"
          action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
          >
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="name" class="form-label">Name</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="name"
                  name="name"
                  />
              </div>
              <div class="col">
                <label for="email" class="form-label">Email</label>
                <input 
                  type="email" 
                  class="form-control" 
                  id="email"
                  name="email"
                  />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="password" class="form-label">Password</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="password" 
                  name="password" 
                  />
              </div>
              <div class="col">
                <label for="confirm-password" class="form-label"
                  >Confirm Password</label
                >
                <input
                  type="password"
                  class="form-control"
                  id="confirm-password"
                  name="confirm_password"
                />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role">
              <option value="">Select an option</option>
              <option value="user">User</option>
              <option value="seller">Seller</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
          <input
            type="hidden"
            name="csrf_token"
            value="<?php echo CSRF::getToken( 'add_admin_form' ); ?>"
            />
        </form>
      </div>
      <div class="text-center">
        <a href="/admin" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Admin</a
        >
      </div>
    </div>

<?php
require dirname(__DIR__) . '/parts/footer.php';
?>