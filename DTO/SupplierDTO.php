<?php 
class SupplierDTO {
    public $id;
    public $address;
    public $phone;
    public $name;
    public $status;
    public $id_admin;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->address = $data['address'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->status = $data['status'] ?? 1;
        $this->id_admin = $data['id_admin'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "SupplierDTO[id=%s, name=%s, status=%s, admin_id=%s]",
            $this->id,
            $this->name,
            $this->status ? 'active' : 'inactive',
            $this->id_admin
        );
    }
}?>