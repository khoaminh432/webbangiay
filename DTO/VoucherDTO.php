<?php
class VoucherDTO {
    public $id;
    public $name;
    public $deduction;
    public $description;
    public $date_start;
    public $date_end;
    public $id_admin;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->deduction = $data['deduction'] ?? 0.0;
        $this->description = $data['description'] ?? null;
        $this->date_start = $data['date_start'] ?? null;
        $this->date_end = $data['date_end'] ?? null;
        $this->id_admin = $data['id_admin'] ?? null;
        $this->is_active = $data['is_active'] ?? true;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "VoucherDTO[id=%s, name=%s, deduction=%.2f, active=%s, valid=%s to %s]",
            $this->id,
            $this->name,
            $this->deduction,
            $this->is_active ? 'yes' : 'no',
            $this->date_start,
            $this->date_end ?? 'indefinite'
        );
    }
}?>