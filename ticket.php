<?php
include './header.php';
require_once './src/ticket.php';
require_once './src/ticket-event.php';
require './src/helper-functions.php';

$err = '';
$msg = '';

// Fetching users
$sqlUsers = "SELECT id, name FROM users ORDER BY name ASC";
$usersResult = $db->query($sqlUsers);
$users = $usersResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $comment = $_POST['comment'];
    $priority = $_POST['priority'];
    $selectedUsers = $_POST['users'] ?? [];

    // Validation
    $validationErrors = [];

    if (empty($name)) {
        $validationErrors[] = "Please enter requester name.";
    }

    // Perform similar validation for other fields

    if (!empty($validationErrors)) {
        $err = implode(' ', $validationErrors);
    } else {
        try {
            $ticket = new Ticket([
                'title' => $subject,
                'body' => $comment,
                'priority' => $priority,
            ]);

            $savedTicket = $ticket->save();

            $event = new Event([
                'ticket' => $savedTicket->id,
                'user' => $user->id,
                'body' => 'Ticket created',
            ]);
            $event->save();
            

            $msg = "Ticket generated successfully";
        } catch (Exception $e) {
            $err = "Failed to generate ticket";
        }
    }
}
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">New ticket</li>
        </ol>

        <div class="card mb-3">
            <div class="card-header">
                <h3>Create a new ticket</h3>
            </div>
            <div class="card-body">
                <?php if ($err) : ?>
                    <div class="alert alert-danger text-center my-3" role="alert">
                        <strong>Failed! </strong> <?php echo $err; ?>
                    </div>
                <?php endif ?>

                <?php if ($msg) : ?>
                    <div class="alert alert-success text-center my-3" role="alert">
                        <strong>Success! </strong> <?php echo $msg; ?>
                    </div>
                <?php endif ?>

                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <?php $fields = ['name' => 'Name', 'email' => 'Email', 'phone' => 'Phone', 'subject' => 'Subject', 'comment' => 'Comment']; ?>
                    <?php foreach ($fields as $field => $label) : ?>
                        <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                            <label for="<?php echo $field ?>" class="col-sm-12 col-lg-2 col-md-2 col-form-label"><?php echo $label ?></label>
                            <div class="col-sm-8">
                                <?php if ($field == 'comment') : ?>
                                    <textarea name="<?php echo $field ?>" class="form-control" placeholder="Enter <?php echo $field ?>"></textarea>
                                <?php else : ?>
                                    <input type="text" name="<?php echo $field ?>" class="form-control" placeholder="Enter <?php echo $field ?>">
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>

                    <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                        <label for="users" class="col-sm-12 col-lg-2 col-md-2 col-form-label">Assigned Users</label>
                        <div class="col-sm-8">
                            <select name="users[]" class="form-control selectpicker" multiple data-live-search="true">
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?php echo $user['id'] ?>"> <?php echo $user['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12">
                        <label for="priority" class="col-sm-12 col-lg-2 col-md-2 col-form-label">Priority</label>
                        <div class="col-sm-8">
                            <select name="priority" class="form-control">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-lg btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <!-- Sticky Footer -->
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright © Your Website 2019</span>
            </div>
        </div>
    </footer>
</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="./index.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin.min.js"></script>

<!-- Demo scripts for this page-->
<script src="js/demo/datatables-demo.js"></script>
<script src="js/demo/chart-area-demo.js"></script>
<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker();
    });
</script>
</body>

</html>
