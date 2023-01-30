<?php
if ( !Authentication::whoCanAccess('user') ) {
    header( 'Location: /login' );
    exit;
}


CSRF::generateToken( 'message_form' );

$user_id = $_SESSION['user']['id'];

$messages = Message::getAllMessages($user_id);

if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

  $rules = [
    'fromUser' => 'required',
    'fromSeller' => 'required',
    'csrf_token' => 'message_form_csrf_token',
  ];

  $error = FormValidation::validate(
    $_POST,
    $rules
  );

  if ( !$error ) {
    Message::createUserMessage(
        $_POST['fromUser'],
        $_SESSION['user']['id']
    );

    Message::createSellerMessage(
        $_POST['fromSeller'],
        $_SESSION['user']['id']
    );

    CSRF::removeToken( 'message_form' );

    header("Location: /dashboard");
    exit;

  } 
} 


require dirname(__DIR__) . '/parts/header.php';
?>


<div class="container mx-auto my-5" style="max-width: 800px;">
      <h1 class="h1 mb-4 text-center">Messages</h1>
      <div class="row">
        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                Users
              </h5>
              
              <?php if(Authentication::whoCanAccess('useronly')): ?>
                <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
                    <div class="mb-3">
                        <label for="fromUser" class="form-label">Content</label>
                        <textarea
                        class="form-control"
                        id="fromUser"
                        rows="10"
                        name="fromUser"
                        ></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::getToken("manage_form"); ?>" />
                </form>
              <?php endif;?>
              <div class='text-center'>
                    <label for="usermeg" class="form-label">Content</label>
                    <input type="text" class="form-control" id='usermeg;>
              </div>
            </div>
          </div>
        </div>


        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                Sellers
              </h5>
                <?php if(Authentication::whoCanAccess('selleronly')): ?>
                <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
                    <div class="mb-3">
                        <label for="fromSeller" class="form-label">Reply</label>
                        <textarea
                        class="form-control"
                        id="fromSeller"
                        rows="10"
                        name="fromSeller"
                        ></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::getToken("manage_form"); ?>" />
                </form>
                <?php endif; ?>
                <div>
                    <div class='text-center'>
                    
                        <label for="sellermeg" class="form-label">Reply</label>
                        <input type="text" class="form-control" id='sellermeg>
                    </div>
                </div>
            </div>
          </div>
        </div> 

      </div> 




      <div class="mt-4 text-center">
        <a href="/dashboard" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back</a
        >
      </div>
    </div>

<?php
require dirname(__DIR__) . '/parts/header.php';
?>