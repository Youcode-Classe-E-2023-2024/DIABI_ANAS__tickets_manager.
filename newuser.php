<?php
include './header1.php';
require_once './src/user.php';
require './src/helper-functions.php';

$err = '';
$msg = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_pass = $_POST['confirm-password'];

    if (strlen($name) < 1) {
        $err = "Please enter user name";
    } else if (strlen($email) < 1) {
        $err = "Please enter email";
    } else if (!isValidEmail($email)) {
        $err = "Please enter a valid email";
    } else if (strlen($phone) < 1) {
        $err = "Please enter phone no";
    } else if (!isValidPhone($phone)) {
        $err = "Please enter a valid phone no";
    } else if (strlen($password) < 1) {
        $err = "Please enter a password";
    } else if (strlen($password) < 8) {
        $err = "Password should be at least 8 characters";
    } else if ($password != $confirm_pass) {
        $err = "Passwords do not match";
    } else {
        try {
            $user = new User([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'member',
                'last_password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $user->save();
            $msg = "User created successfully";
        } catch (Exception $e) {
            $err = "Unable to create user";
        }
    }
}
?>
<div id="content-wrapper" class="bg-light">
    <div class="container-fluid">
        <ol class="breadcrumb bg-secondary">
            <li class="breadcrumb-item">
                <a href="#" class="text-light">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-light">New user registration</li>
        </ol>

        <div class="card mb-3">
            <div class="card-header bg-primary text-light">
                <h3>Create a new account</h3>
            </div>

            <div class="card-body">
                <?php if (strlen($err) > 1) : ?>
                    <div class="alert alert-danger text-center my-3" role="alert">
                        <strong>Failed! </strong> <?php echo $err; ?>
                    </div>
                <?php endif ?>

                <?php if (strlen($msg) > 1) : ?>
                    <div class="alert alert-success text-center my-3" role="alert">
                        <strong>Success! </strong> <?php echo $msg; ?>
                    </div>
                <?php endif ?>

                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation" novalidate>
                    <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                        <label for="name" class="col-sm-12 col-lg-2 col-md-2 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                            <div class="invalid-feedback">Please enter user name.</div>
                        </div>
                    </div>

                    <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                        <label for="email" class="col-sm-12 col-lg-2 col-md-2 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="text" name="email" class="form-control" placeholder="Enter email" required>
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>
                    </div>

                    <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                        <label for="phone" class="col-sm-12 col-lg-2 col-md-2 col-form-label">Phone</label>
                        <div class="col-sm-8">
                            <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
                            <div class="invalid-feedback">Please enter a valid phone number.</div>
                        </div>
                    </div>

                    <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                        <label for="password" class="col-sm-12 col-lg-2 col-md-2 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            <div class="invalid-feedback">Password should be at least 8 characters.</div>
                        </div>
                    </div>

                    <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                        <label for="confirm-password" class="col-sm-12 col-lg-2 col-md-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="confirm-password" class="form-control" placeholder="Confirm password" required>
                            <div class="invalid-feedback">Passwords do not match.</div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-lg btn-success">Create Account</button>
                        <a href="./index.php" class="btn btn-lg btn-secondary">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
    
</div>
<!-- /.content-wrapper -->
