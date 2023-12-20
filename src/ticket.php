<?php
class Ticket
{

    public $title = '';

    public $body = '';

    public $requester = null;

    public $id = '';

    public $created_at;

    public $team_member = null;

    public $status = '';

    public $priority = '';

    public $rating = '';

     public $agents = [];

    private $db = null;

    public function __construct($data = null)
    {
        $this->title =  isset($data['title']) ? $data['title'] : null;
        $this->body = isset($data['body']) ? $data['body'] : null;
        $this->requester = isset($data['requester']) ? $data['requester'] : null;
        $this->team_member = isset($data['team_member']) ? $data['team_member'] : null;
        $this->status = isset($data['status']) ? $data['status'] : 'open';
        $this->priority = isset($data['priority']) ? $data['priority'] : 'low';

        $this->db = Database::getInstance();

        return $this;
    }

    public function assignAgents(array $agentIds): void
    {
        foreach ($agentIds as $agentId) {
            $this->assignAgent($agentId);
        }
    }



    public function getCreatedAt() : ?string
    {
        return $this->created_at ?? '';
    }



    public static function getAgentsByTicketId($ticketId): array
{
    $sql = "SELECT users.* FROM users
            JOIN ticket_agents ON users.id = ticket_agents.user_id
            WHERE ticket_agents.ticket_id = '$ticketId'";
    
    $self = new static;
    $agents = [];
    $result = $self->db->query($sql);

    while ($row = $result->fetch_object()) {
        $agents[] = $row;
    }

    return $agents;
}
    public function assignAgent(int $agentId): void
    {
        $sql = "INSERT INTO ticket_user (ticket_id, user_id) VALUES ('$this->id', '$agentId')";

        if ($this->db->query($sql) === false) {
            throw new Exception($this->db->error);
        }
    }

    public function save(): Ticket
    {
        $sql = "INSERT INTO ticket (title, body, requester,  team_member, status, priority)
                VALUES ('$this->title', '$this->body', '$this->requester', '$this->team_member', '$this->status', '$this->priority')";

        if ($this->db->query($sql) === false) {
            throw new Exception($this->db->error);
        }

        $this->id = $this->db->insert_id;

        // Assign agents to the ticket
        $this->assignAgents($this->agents);

        return $this;
    }

    public function assignUsers(array $userIds): void
    {
        foreach ($userIds as $userId) {
            $this->assignUser($userId);
        }
    }

    public function assignUser(int $userId): void
    {
        $sql = "INSERT INTO ticket_agents (ticket_id, user_id) VALUES ('$this->id', '$userId')";

        if ($this->db->query($sql) === false) {
            throw new Exception($this->db->error);
        }
    }
    public static function find($id): Ticket
    {
        $sql = "SELECT * FROM ticket WHERE id = '$id'";
        $self = new static;
        $res = $self->db->query($sql);
        if ($res->num_rows < 1) {
            return false;
        }

        $self->populateObject($res->fetch_object());
        return $self;
    }

    public static function findAll(): array
    {
        $sql = "SELECT * FROM ticket ORDER BY id DESC";
        $tickets = [];
        $self = new static;
        $res = $self->db->query($sql);

        if ($res->num_rows < 1) {
            return new static;
        }

        while ($row = $res->fetch_object()) {
            $ticket = new static;
            $ticket->populateObject($row);
            $tickets[] = $ticket;
        }

        return $tickets;
    }

    public static function findByStatus($status): array
    {
        $sql = "SELECT * FROM ticket WHERE status = '$status' ORDER BY id DESC";
        $self = new static;
        $tickets = [];
        $res = $self->db->query($sql);
    
        // Check if there are no results
        if ($res === false || $res->num_rows === 0) {
            return [];
        }
    
        while ($row = $res->fetch_object()) {
            $tickets[] = $row;
        }
    
        return $tickets;
    }

    public static function changeStatus($id, $status): bool
    {
        $self = new static;
        $sql = "UPDATE ticket SET status = '$status' WHERE id = '$id'";
        return $self->db->query($sql);
    }

    public static function delete($id): bool
    {
        $sql = "DELETE FROM ticket WHERE id = '$id'";
        // print_r($sql);die();
        $self = new static;
        return $self->db->query($sql);
    }

    public static function setRating($id, $rating): bool
    {
        $sql = "UPDATE ticket SET rating = '$rating' WHERE id = '$id'";
        $self = new static;
        return $self->db->query($sql);
    }

    public static function setPriority($id, $priority): bool
    {
        $sql = "UPDATE ticket SET priority = '$priority' WHERE id = '$id'";
        $self = new static;
        return $self->db->query($sql);
    }

    public function displayStatusBadge(): string
    {
        $badgeType = '';
        if ($this->status == 'open') {
            $badgeType = 'danger';
        } else if ($this->status == 'pending') {
            $badgeType = 'warning';
        } else if ($this->status == 'solved') {
            $badgeType = 'success';
        } else if ($this->status == 'closed') {
            $badgeType = 'info';
        }

        return '<div class="badge badge-' . $badgeType . '" role="badge"> ' . ucfirst($this->status) . '</div>';
    }

    public function populateObject($object): void
    {
        foreach ($object as $key => $property) {
            $this->$key = $property;
        }

        // Populate agents based on ticket ID
        $sql = "SELECT user_id FROM ticket_agents WHERE ticket_id = '$this->id'";
        $result = $this->db->query($sql);

        while ($row = $result->fetch_assoc()) {
            $this->agents[] = $row['user_id'];
        }
    }

    public function update($id): Ticket
{
    $sql = "UPDATE ticket set `team_member` = '$this->team_member', `title` = '$this->title', `body` = '$this->body',
         `requester`='$this->requester', `status`= '$this->status', `priority`='$this->priority'
          Where id = '$id'";

    if ($this->db->query($sql) === false) {
        $error = $this->db->error;
        throw new Exception("Failed to update ticket. Error: $error");
    }

    return self::find($id);
}

    public function unassigned()
    {

        $sql = "SELECT * FROM ticket WHERE team_member = '' ORDER BY id DESC";

        $self = new static;
        $tickets = [];
        $res = $self->db->query($sql);

        while ($row = $res->fetch_object()) {
            $tickets[] = $row;
        }

        return $tickets;

    }

    public static function findByMember($member)
     {

        $sql = "SELECT * FROM ticket WHERE team_member = '$member' ORDER BY id DESC";
        
        $self = new static;
        $tickets = [];
        $res = $self->db->query($sql);
        
        while($row = $res->fetch_object()){
            $ticket = new static;
            $ticket->populateObject($row);
            $tickets[] = $ticket;
        }

        return $tickets;

     }

}
