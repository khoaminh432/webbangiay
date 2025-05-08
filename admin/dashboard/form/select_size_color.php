<?php
require_once __DIR__ . '/../../../dao/SizeDao.php';
require_once __DIR__ . '/../../../dao/ColorDao.php';
require_once __DIR__ . "/../../../dao/ProductSizeColorDao.php";
$sizeDao = new SizeDao();
$colorDao = new ColorDao();

$sizes = $sizeDao->view_all();
$colors = $colorDao->view_all();
?>

<!-- Thêm thẻ Ionicon -->
<style>
    .matrix-form-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.3);
        z-index: 1001;
        max-width: 90%;
        max-height: 90%;
        overflow: auto;
    }

    .matrix-form-close-btn {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 30px;
        color: #ff4d4f;
        cursor: pointer;
        transition: transform 0.2s ease;
        z-index: 1000;
    }

    .matrix-form-close-btn:hover {
        transform: scale(1.2);
    }

    .matrix-form-title {
        text-align: center;
        color: #333;
        font-size: 28px;
    }

    .matrix-form-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .matrix-form-table th,
    .matrix-form-table td {
        padding: 15px 20px;
        border: 1px solid #ddd;
        text-align: center;
        font-size: 14px;
    }

    .matrix-form-table th {
        background-color: #007bff;
        color: white;
        text-transform: uppercase;
    }

    .matrix-form-table td {
        background-color: #fff;
        color: black;
        transition: background-color 0.3s ease;
    }

    .matrix-form-table td:hover {
        background-color: #e2f0ff;
    }

    .matrix-color-box {
        width: 30px;
        height: 30px;
        display: inline-block;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .matrix-form-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .matrix-form-checkbox:checked {
        transform: scale(1.2);
    }

    .matrix-form-button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: block;
        margin: 30px auto 0;
    }

    .matrix-form-button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .matrix-form-table th,
        .matrix-form-table td {
            font-size: 12px;
            padding: 12px 15px;
        }

        .matrix-form-button {
            font-size: 14px;
        }
    }
</style>

<div id="sizeColorMatrixForm" class="matrix-form-container" style="display:none;" >
    <div class="matrix-form-close-btn" onclick="closeForm()">
        <ion-icon name="close-circle-outline"></ion-icon>
    </div>

    <h2 class="matrix-form-title">Chọn các cặp Size và Color (Ma Trận)</h2>

    <form  id="sizeColorForm">
    <input type="hidden" name="product_id" value="<?= $object_id ?>">
        <table class="matrix-form-table">
            <thead>
                <tr>
                    <th>Size \ Color</th>
                    <?php foreach ($colors as $color): ?>
                        <th>
                            <div class="matrix-color-box" style="background-color: <?= htmlspecialchars($color->hex_code) ?>"></div><br>
                            <?= htmlspecialchars($color->name) ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($sizes as $size): ?>
                    <tr>
                        <td><?= htmlspecialchars($size->size_number) ?></td>
                        <?php foreach ($colors as $color): ?>
                            <td>
                                <input 
                                    type="checkbox" 
                                    class="matrix-form-checkbox"
                                    name="size_color_combinations[]" 
                                    value="<?= $size->id . '_' . $color->id ?>"
                                    <?=!$psc_table->exists($object_id,$size->id,$color->id)?"":" checked"?>
                                    >
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <button type="submit" class="matrix-form-button">Lưu lựa chọn</button>
    </form>
</div>

<script>
    
    function closeForm() {
        document.getElementById('sizeColorMatrixForm').style.display = 'none';
    }
</script>
<script>
document.getElementById('sizeColorForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Ngăn reload

    const form = e.target;
    const formData = new FormData(form);
    Swal.fire({
            title: "Bạn có chắc chắn muốn lưu thay đổi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
          }).then(result => {
            if (result.isConfirmed) {
              onConfirm(formData);
            }
          });    
    function onConfirm(formData){fetch('handle/admin/save_size_color.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire("Thành công", response.message, "success");
            closeForm();
        } else {
            Swal.fire("Lỗi", response.message, "error");
        }
    })
    .catch(error => {
        alert('❌ Lỗi AJAX: ' + error);
    });}
});
</script>

