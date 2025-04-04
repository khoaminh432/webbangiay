<?php 
class UserDTO {
    public $id;
    public $email;
    public $password;
    public $status;
    public $username;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->status = $data['status'] ?? 1;
        $this->username = $data['username'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
    public function __toString() {
        return sprintf(
            "UserDTO[id=%s, username=%s, email=%s, status=%s]",
            $this->id,
            $this->username,
            $this->email,
            $this->status ? 'active' : 'inactive'
        );
    }
}?>