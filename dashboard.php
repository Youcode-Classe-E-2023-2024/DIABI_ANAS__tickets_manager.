<?php
include './header.php';
require_once './src/ticket.php';
require_once './src/user.php';

$ticket = new Ticket();
$allTicket = $ticket::findAll();

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    try {
        $ticket->delete($id);
        echo '<script>alert("Ticket deleted successfully");window.location = "./dashboard.php"</script>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
<div class="container mt-3">
    <h2>Dashboard</h2>
    <a class="btn btn-primary mb-3" href="./ticket.php"><i class="bi bi-plus"></i> New Ticket</a>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Subject</th>
                            <th>Agent</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allTicket as $ticket) : ?>
                            <tr>
                                <td><a href="./ticket-details.php?id=<?php echo $ticket->id ?>"><?php echo $ticket->title ?></a></td>
                                <?php $agentId = $ticket->team_member; ?>
                                <?php $agent = User::find($agentId); ?>
                                <td><?php echo $agent ? $agent->name : 'No Agent Assigned'; ?></td>
                                <?php $date = new DateTime($ticket->created_at) ?>
                                <td><?php echo $date->format('d-m-Y H:i:s') ?> </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="./ticket-details.php?id=<?php echo $ticket->id ?>" class="btn btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                        <a onclick="return confirm('Are you sure to delete')" href="?del=<?php echo $ticket->id; ?>" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Delete</a>
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
<?php include './footer.php' ?>
