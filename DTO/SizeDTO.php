<?php
class SizeDTO {
    public $id;
    public $size_number;
    public $description;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->size_number = $data['size_number'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "SizeDTO[id=%s, size_number=%s, description=%s]",
            $this->id,
            $this->size_number,
            $this->description
        );
    }
}
?>
