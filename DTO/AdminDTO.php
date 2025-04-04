<?php
class AdminDTO {
    public $id;
    public $name;
    public $email;
    public $password;
    public $position;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->position = $data['position'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "AdminDTO[id=%s, name=%s, position=%s, email=%s]",
            $this->id,
            $this->name,
            $this->position,
            $this->email
        );
    }
}
?>