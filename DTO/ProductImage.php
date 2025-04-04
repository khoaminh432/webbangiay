<?php
class ProductImageDTO {
    public $id;
    public $image_url;
    public $id_product;
    public $is_primary;
    public $created_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->image_url = $data['image_url'] ?? '';
        $this->id_product = $data['id_product'] ?? null;
        $this->is_primary = $data['is_primary'] ?? false;
        $this->created_at = $data['created_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "ProductImageDTO[id=%s, product_id=%s, primary=%s, url=%s]",
            $this->id,
            $this->id_product,
            $this->is_primary ? 'yes' : 'no',
            $this->image_url
        );
    }
}?>