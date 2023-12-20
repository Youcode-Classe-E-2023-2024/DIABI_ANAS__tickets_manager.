<?php
include './header.php';
require_once './src/ticket.php';
require_once './src/user.php';

$ticket = new Ticket();
$allTicket = $ticket::findByStatus('open');

$user = new User();
?>
<div class="container mt-3">
    <h2>Dashboard Overview</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Subject</th>
                            <th>Agent</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allTicket as $ticket) : ?>
                            <tr>
                                <td><a href="./ticket-details.php?id=<?php echo $ticket->id ?>"><?php echo $ticket->title ?></a></td>
                                <?php $usr = $user::find($ticket->team_member); ?>
                                <td><?php echo $usr ? $usr->name : ''; ?></td>
                                <td><button class="btn btn-danger"><?php echo $ticket->status ?></button></td>
                                <?php $date = new DateTime($ticket->created_at) ?>
                                <td><?php echo $date->format('d-m-Y H:i:s') ?> </td>
                                <td>
                                    <a class="btn btn-outline-primary" href="./ticket-details.php?id=<?php echo $ticket->id ?>">View</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="mt-5">
        <p class="text-center">&copy; Your Website 2019</p>
    </footer>
</div>

<?php include './footer.php' ?>
