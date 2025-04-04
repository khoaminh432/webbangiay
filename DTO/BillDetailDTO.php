<?php
class BillDetailDTO {
    public $id;
    public $bill_id;
    public $price_product;
    public $shipping_fee;
    public $notes;
    public $created_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->bill_id = $data['bill_id'] ?? null;
        $this->price_product = $data['price_product'] ?? 0.0;
        $this->shipping_fee = $data['shipping_fee'] ?? 0.0;
        $this->notes = $data['notes'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "BillDetailDTO[id=%s, bill_id=%s, product_price=%.2f, shipping=%.2f]",
            $this->id,
            $this->bill_id,
            $this->price_product,
            $this->shipping_fee
        );
    }
}?>