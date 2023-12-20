<?php

 class Comment {


    public $ticket = '';
    public $team_member = '';
    public $private = '';
    public $body = '';

    private $db = null;

    public function __construct($data = null)
    {
        $this->ticket = isset($data['ticket-id']) ? $data['ticket-id'] : null;
        $this->team_member = isset($data['team-member']) ? $data['team-member'] : null ;
        $this->body = isset($data['body']) ? $data['body'] : null ;
     
        $this->db = Database::getInstance();
        return $this;
    }

    public function save(): Comment
    {
        // Assuming 'users' is the table where user information is stored
        $sql = "INSERT INTO comments (ticket, user, body)
                SELECT '$this->ticket', u.id, '$this->body'
                FROM users u
                WHERE u.id = '$this->team_member';";

        if ($this->db->query($sql) === false) {
            throw new Exception($this->db->error);
        }

        $id = $this->db->insert_id;
        return self::find($id);
    }

    public static function find($id) : Comment
    {
        $sql ="SELECT * FROM comments WHERE id = '$id'";
        $self = new static;
        $res = $self->db->query($sql);
        if($res->num_rows < 1) return $self;
        $self->populateObject($res->fetch_object());
        return $self;
    }

    public function populateObject($object) : void{

        foreach($object as $key => $property){
            $this->$key = $property;
        }
    }

    public static function findByTicket($id): array
    {
        $sql = "SELECT c.*, u.name as user_name 
                FROM comments c
                JOIN users u ON c.user = u.id
                WHERE c.ticket = '$id'";

        $comments = [];
        $self = new static;
        $res = $self->db->query($sql);

        if ($res->num_rows < 1) return $comments;

        while ($row = $res->fetch_object()) {
            $comment = new static;
            $comment->populateObject($row);
            $comments[] = $comment;
        }

        return $comments;
    }
 }