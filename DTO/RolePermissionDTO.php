<?php
class RolePermissionDTO {
    public $position_id;
    public $permission_id;

    public function __construct($data) {
        $this->position_id   = $data['position_id'] ?? null;
        $this->permission_id = $data['permission_id'] ?? null;
    }
    public function __toString() {
        return sprintf(
            "RolePermissionDTO[position_id=%s, permission_id=%s, assigned_at=%s]",
            $this->position_id,
            $this->permission_id
        );
    }
}
?>
