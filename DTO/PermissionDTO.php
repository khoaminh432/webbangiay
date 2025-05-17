<?php
class PermissionDTO {
    public $permission_id;
    public $permission_name;
    public $created_at;

    public function __construct($data) {
        $this->permission_id = $data['permission_id'] ?? null;
        $this->permission_name = $data['permission_name'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "PermissionDTO[permission_id=%s, permission_name=%s, created_at=%s]",
            $this->permission_id,
            $this->permission_name,
            $this->created_at
        );
    }
}
?>
