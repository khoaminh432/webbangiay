<?php
class RoleDTO {
    public $position_id;
    public $role_name;
    public $created_at;

    public function __construct($data) {
        $this->position_id = $data['position_id'] ?? null;
        $this->role_name = $data['role_name'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "RoleDTO[position_id=%s, role_name=%s, created_at=%s]",
            $this->position_id,
            $this->role_name,
            $this->created_at
        );
    }
}
?>
