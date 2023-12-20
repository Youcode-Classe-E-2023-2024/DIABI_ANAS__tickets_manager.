<?php
include './header.php';
require_once './src/ticket.php';
require_once './src/user.php';

$tickets = Ticket::findByMember($user->id);

?>
<div id="content-wrapper">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">My Tickets</li>
        </ol>
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Agent</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket) : ?>
                                <tr>
                                    <td><a href="./ticket-details.php?id=<?php echo $ticket->id ?>"><?php echo $ticket->title ?></a></td>
                                    <td><?php echo ($ticket->team_member == '') ? 'No Agent Assigned' : (User::find($ticket->team_member) ? User::find($ticket->team_member)->name : 'Agent Not Found'); ?></td>
                                    <td>
                                        <button class="btn <?php echo ($ticket->status == 'solved') ? 'btn-success' : 'btn-warning'; ?>"><?php echo $ticket->status ?></button>
                                    </td>
                                    <td><?php echo (new DateTime($ticket->created_at))->format('d-m-Y H:i:s'); ?> </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="./ticket-details.php?id=<?php echo $ticket->id ?>">View</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include './footer.php'; ?>
