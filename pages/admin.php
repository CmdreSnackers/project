<?php
if ( !Authentication::whoCanAccess('admin') ) {
    header('Location: /dashboard');
    exit;
}

CSRF::generateToken('delete_user_form');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = FormValidation::validate(
        $_POST,
        [
            'user_id' => 'required',
            'csrf_token' => 'delete_admin_form_csrf_token'
        ]
    );

    if(!$error) {

        User::delete($_POST['user_id']);

        CSRF::removeToken('delete_admin_form');

        header('Location: /admin');
        exit;
    }

} 

require dirname(__DIR__) . '/parts/header.php';
?>

<div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Sellers</h1>
        <div class="text-end">
          <a href="/admin-add" class="btn btn-primary btn-sm"
            >Add New User</a
          >
        </div>
      </div>
      <div class="card mb-2 p-4">
        <?php require dirname(__DIR__) . '/parts/error-box.php'; ?>
        <table class="table">
          <thead>
            <tr>
            <th scope="col">No.</th>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach( User::getAllUsers() as $index => $user ) : ?>
              <tr>
                <td><?php echo $index+1; ?></td>
                <th scope="row"><?php echo $user['id']; ?></th>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                  <?php
                    switch( $user['role'] ) {
                      case 'admin':
                        echo '<span class="badge bg-primary">' . $user['role'] .'</span>';
                        break;
                      case 'seller':
                        echo '<span class="badge bg-warning">' . $user['role'] .'</span>';
                        break;
                      case 'user':
                        echo '<span class="badge bg-success">' . $user['role'] .'</span>';
                        break;
                    }
                  ?>
                </td>
                <td class="text-end">
                  <div class="buttons">
                    <a
                      href="/admin-edit?id=<?php echo $user['id']; ?>"
                      class="btn btn-success btn-sm me-2"
                      ><i class="bi bi-pencil"></i
                    ></a>
                    <?php if($_SESSION['user']['id'] !== $user['id']): ?>
                    <!-- delete button -->
                    <!-- Button trigger modal -->
                    <button 
                      type="button" 
                      class="btn btn-danger btn-sm" 
                      data-bs-toggle="modal" 
                      data-bs-target="#user-<?php echo $user['id']; ?>">
                      <i class="bi bi-trash"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="user-<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-start">
                            Delete this User? (<?php echo $user['name'] ?>)
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                              <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                              <input type="hidden" name="csrf_token" value="<?php echo CSRF::getToken('delete_admin_form'); ?>">
                              <button type="submit" class="btn btn-danger">Confirm</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php endif; ?>
                    <!-- delete button -->
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="text-center">
        <a href="/dashboard" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
        >
      </div>
    </div>
<?php
require dirname(__DIR__) . '/parts/footer.php';
?>