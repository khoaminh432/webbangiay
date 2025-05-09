<?php
class ColorDTO {
    public $id;
    public $name;
    public $hex_code;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->hex_code = $data['hex_code'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "ColorDTO[id=%s, name=%s, hex=%s]",
            $this->id,
            $this->name,
            $this->hex_code
        );
    }
}
?>
