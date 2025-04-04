<?php

class UserDTO {
    public $id;
    public $email;
    public $password;
    public $status;
    public $username;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->status = $data['status'] ?? 1;
        $this->username = $data['username'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "UserDTO[id=%s, username=%s, email=%s, status=%s]",
            $this->id,
            $this->username,
            $this->email,
            $this->status ? 'active' : 'inactive'
        );
    }
}

class AdminDTO {
    public $id;
    public $name;
    public $email;
    public $password;
    public $position;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->position = $data['position'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "AdminDTO[id=%s, name=%s, position=%s, email=%s]",
            $this->id,
            $this->name,
            $this->position,
            $this->email
        );
    }
}

class TypeProductDTO {
    public $id;
    public $name;
    public $id_admin;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->id_admin = $data['id_admin'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "TypeProductDTO[id=%s, name=%s, admin_id=%s]",
            $this->id,
            $this->name,
            $this->id_admin
        );
    }
}

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

class InformationReceiveDTO {
    public $id;
    public $address;
    public $name;
    public $phone;
    public $id_user;
    public $is_default;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->address = $data['address'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->id_user = $data['id_user'] ?? null;
        $this->is_default = $data['is_default'] ?? false;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "InformationReceiveDTO[id=%s, name=%s, phone=%s, user_id=%s]",
            $this->id,
            $this->name,
            $this->phone,
            $this->id_user
        );
    }
}

class SupplierDTO {
    public $id;
    public $address;
    public $phone;
    public $name;
    public $status;
    public $id_admin;
    public $created_at;
    public $updated_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->address = $data['address'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->status = $data['status'] ?? 1;
        $this->id_admin = $data['id_admin'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function __toString() {
        return sprintf(
            "SupplierDTO[id=%s, name=%s, status=%s, admin_id=%s]",
            $this->id,
            $this->name,
            $this->status ? 'active' : 'inactive',
            $this->id_admin
        );
    }
}

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
}

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
}

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
}

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
}

class BillProductDTO {
    public $id;
    public $id_bill;
    public $id_product;
    public $quantity;
    public $unit_price;
    public $created_at;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->id_bill = $data['id_bill'] ?? null;
        $this->id_product = $data['id_product'] ?? null;
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