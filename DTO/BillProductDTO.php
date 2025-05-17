<?php
class BillProductDTO {
    public $id;
    public $id_bill;
    public $id_product;
    public $id_color;
    public $id_size;
    public $quantity;
    public $unit_price;
    public $created_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->id_bill = $data['id_bill'] ?? null;
        $this->id_product = $data['id_product'] ?? null;
        $this->id_color = $data['id_color'] ?? null;
        $this->id_size = $data['id_size'] ?? null;
        $this->quantity = $data['quantity'] ?? 1;
        $this->unit_price = $data['unit_price'] ?? 0.0;
        $this->created_at = $data['created_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "BillProductDTO[id=%s, bill_id=%s, product_id=%s, qty=%s, price=%.2f]",
            $this->id,
            $this->id_bill,
            $this->id_product,
            $this->quantity,
            $this->unit_price
        );
    }
}
?>