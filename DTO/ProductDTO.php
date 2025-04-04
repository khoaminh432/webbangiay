<?php 
class ProductDTO {
    public $id;
    public $name;
    public $quantity;
    public $description;
    public $price;
    public $weight;
    public $id_voucher;
    public $id_type_product;
    public $id_admin;
    public $id_supplier;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->quantity = $data['quantity'] ?? 0;
        $this->description = $data['description'] ?? null;
        $this->price = $data['price'] ?? 0.0;
        $this->weight = $data['weight'] ?? 0.0;
        $this->id_voucher = $data['id_voucher'] ?? null;
        $this->id_type_product = $data['id_type_product'] ?? null;
        $this->id_admin = $data['id_admin'] ?? null;
        $this->id_supplier = $data['id_supplier'] ?? null;
        $this->is_active = $data['is_active'] ?? true;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "ProductDTO[id=%s, name=%s, price=%.2f, stock=%s, active=%s]",
            $this->id,
            $this->name,
            $this->price,
            $this->quantity,
            $this->is_active ? 'yes' : 'no'
        );
    }
}
?>