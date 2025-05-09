<?php
class ProductSizeColorDTO {
    public $id;
    public $id_product;
    public $id_size;
    public $id_color;
    public $quantity;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->id_product = $data['id_product'] ?? null;
        $this->id_size = $data['id_size'] ?? null;
        $this->id_color = $data['id_color'] ?? null;
        $this->quantity = $data['quantity'] ?? 0;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "PSC_DTO[id=%s, product_id=%s, size_id=%s, color_id=%s, qty=%d]",
            $this->id,
            $this->id_product,
            $this->id_size,
            $this->id_color,
            $this->quantity
        );
    }
}
?>
