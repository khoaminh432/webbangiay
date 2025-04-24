<?php
    class TypeProductDTO {
        public $id;
        public $name;
        public $id_admin;
        public $created_at;
        public $updated_at;
        public $is_active;
    
        public function __construct($data) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->id_admin = $data['id_admin'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
            $this->updated_at = $data['updated_at'] ?? null;
            $this->is_active = $data['is_active'] ?? null;
        }
    
        public function __toString() {
            return sprintf(
                "TypeProductDTO[id=%s, name=%s, admin_id=%s]",
                $this->id,
                $this->name,
                $this->id_admin
            );
        }
    }
?>