<?php 
class InformationReceiveDTO {
    public $id;
    public $address;
    public $name;
    public $phone;
    public $id_user;
    public $is_default;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->address = $data['address'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->id_user = $data['id_user'] ?? null;
        $this->is_default = $data['is_default'] ?? false;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "InformationReceiveDTO[id=%s, name=%s, phone=%s, user_id=%s]",
            $this->id,
            $this->name,
            $this->phone,
            $this->id_user
        );
    }
}?>
