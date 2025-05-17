<?php
require_once __DIR__ . "/../../../dao/BillDao.php";
require_once __DIR__ . "/../../../dao/UserDao.php";
require_once __DIR__ . "/../../../dao/PaymentMethodDao.php";

$table_bills = new BillDao();
$table_users = new UserDao();
$table_paymentmethode = new PaymentMethodDao();

// Lấy dữ liệu từ POST
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$status = $_POST['status'] ?? '';
$creator = trim($_POST['creator'] ?? '');

// Lấy toàn bộ bill trước rồi lọc lại
$bills = $table_bills->view_all();

$filtered_bills = array_filter($bills, function ($bill) use
($start_date, $end_date, $status, $creator, $table_users) {
    $bill_time = strtotime($bill->bill_date);

    if ($start_date && $bill_time < strtotime($start_date)) return false;
    if ($end_date && $bill_time > strtotime($end_date . ' 23:59:59')) return false;
    if ($status && strtolower($bill->status) !== strtolower($status)) return false;

    if ($creator) {
        $user = $table_users->get_by_id($bill->id_user);
        if (!$user || stripos($user->username, $creator) === false) return false;
    }

    return true;
});

?>
<script src="js/admin/CRUD_form.js"></script>
<script src="js/admin/checkstatus_object.js"></script>
<link rel="stylesheet" href="css/admin_style/dashboard/table_main.css">
<div class="bill-management object-management active">
    <table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ngày tạo</th>
            <th>Khách hàng</th>
            <th>Phương thức TT</th>
            <th>Tổng tiền</th>
            <th>Địa chỉ giao</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($filtered_bills)):?>
        <tr>
            <td colspan="8" style="text-align: center; font-size: 1.5em;">
                Không có hóa đơn
            </td>
        </tr>
        <?php else:?>
        <?php foreach ($filtered_bills as $bill): 
            $user = $table_users->get_by_id($bill->id_user);
            $paymentMethod = $table_paymentmethode->get_by_id($bill->id_payment_method);
        ?>
            <tr data-id="<?= $bill->id ?>">
                <td><?= $bill->id ?></td>
                <td><?= date('d/m/Y H:i', strtotime($bill->bill_date)) ?></td>
                <td>
                    <?= htmlspecialchars($user->username ?? 'N/A') ?>
                    <div class="user-email"><?= htmlspecialchars($user->email ?? '') ?></div>
                </td>
                <td><?= htmlspecialchars($paymentMethod->name ?? 'N/A') ?></td>
                <td><?= number_format($bill->total_amount, 0, ',', '.') ?>đ</td>
                <td><?= htmlspecialchars($bill->shipping_address) ?></td>
                <?php
$status_order = ['processing', 'shipping', 'completed', 'cancelled'];
$current_status = strtolower($bill->status);
$current_index = array_search($current_status, $status_order);
?>
<td class="status-bill status-<?= $current_status ?>">
    <select name="objectId" class="styled-select status-select" data-object-id="<?= $bill->id ?>" data-current-index="<?= $current_index ?>">
        <?php foreach ($status_order as $index => $status_option):
            $value = 'Bill-' . $status_option;
            $selected = ($current_status === $status_option) ? 'selected' : '';
            $disabled = ($index < $current_index && $status_option !== 'cancelled') ? 'disabled' : ''; // không cho chọn ngược trừ cancelled
        ?>
            <option value="<?= $value ?>" <?= "$selected $disabled" ?>>
                <?= $status_option ?>
            </option>
        <?php endforeach; ?>
    </select>
</td>


                <td class='row button-update'>
                    <button class='action-btn view-btn' data-action='view-bill' data-id='<?= $bill->id ?>'>
                        <ion-icon name="eye-outline"></ion-icon>
                    </button>
                    <button class='action-btn delete-btn' data-action='delete-bill' data-id='<?= $bill->id ?>'>
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endif;?>
    </tbody>
</table>
</div>
