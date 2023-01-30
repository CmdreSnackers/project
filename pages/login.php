<?php
CSRF::generateToken('login_form'); 

if ( Authentication::isLoggedIn() )
{
  header('Location: /dashboard');
  exit;
}

if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = FormValidation::validate(
        $_POST,
        [
        'email' => 'required',
        'password' => 'required',
        'csrf_token' => 'login_form_csrf_token'
        ]
    );

    if(!$error) {
        $user_id = Authentication::login( $email, $password );

        if ( !$user_id ) {
        $error = "Email or password is incorrect";
        } else {

        Authentication::setSession( $user_id );

        CSRF::removeToken('login_form');
  
        header('Location: /dashboard');
        exit;
        } 
    } 
}


require dirname(__DIR__) . '/parts/header.php';
?>


    <div class="container my-5 mx-auto" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">Login</h1>

      <div class="card p-4">
        <?php require dirname(__DIR__) . '/parts/error-box.php'; ?>
        <form method="POST" action="<?php echo $_SERVER['REQUEST_URI'];?>">
          <div class="mb-2">
            <label for="email" class="visually-hidden">Email</label>
            <input
              type="text"
              class="form-control"
              id="email"
              name="email"
              placeholder="email@example.com"
            />
          </div>
          <div class="mb-2">
            <label for="password" class="visually-hidden">Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              placeholder="Password"
            />
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo CSRF::getToken('login_form'); ?>">
        </form>
      </div>

      <div
        class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3"
      >
        <a href="/" class="text-decoration-none small"
          ><i class="bi bi-arrow-left-circle"></i> Go back</a
        >
        <a href="/signup" class="text-decoration-none small"
          >Register
          <i class="bi bi-arrow-right-circle"></i
        ></a>
      </div>
    </div>


<?php
require dirname(__DIR__) . '/parts/footer.php';
?>