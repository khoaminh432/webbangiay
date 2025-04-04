<?php
class BillDTO {
    public $id;
    public $status;
    public $bill_date;
    public $id_user;
    public $id_payment_method;
    public $total_amount;
    public $shipping_address;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->status = $data['status'] ?? 'pending';
        $this->bill_date = $data['bill_date'] ?? null;
        $this->id_user = $data['id_user'] ?? null;
        $this->id_payment_method = $data['id_payment_method'] ?? null;
        $this->total_amount = $data['total_amount'] ?? 0.0;
        $this->shipping_address = $data['shipping_address'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "BillDTO[id=%s, status=%s, amount=%.2f, user_id=%s, date=%s]",
            $this->id,
            $this->status,
            $this->total_amount,
            $this->id_user,
            $this->bill_date
        );
    }
}?>