<?php
    class PaymentMethodDTO {
        public $id;
        public $name;
        public $is_active;
        public $created_at;
        public $updated_at;
    
        public function __construct($data) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->is_active = $data['is_active'] ?? true;
            $this->created_at = $data['created_at'] ?? null;
            $this->updated_at = $data['updated_at'] ?? null;
        }
    
        public function __toString() {
            return sprintf(
                "PaymentMethodDTO[id=%s, name=%s, active=%s]",
                $this->id,
                $this->name,
                $this->is_active ? 'yes' : 'no'
            );
        }
    }
    ?>