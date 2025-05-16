<?php require_once __DIR__."/../../initAdmin.php";?>
<style>
    .admin-form {
    background: linear-gradient(145deg, #1f2023, #38393f);
    border-radius: 20px;
    padding: 40px 50px;
    max-width: 720px;
    width: 90%;
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
    border: 1px solid #444753;
    transition: box-shadow 0.3s ease;
    
  }

  .admin-form:hover {
    box-shadow: 0 25px 50px rgba(0,0,0,0.75);
  }

  .admin-title {
    font-size: 2.3rem;
    font-weight: 700;
    margin-bottom: 40px;
    text-align: center;
    letter-spacing: 0.06em;
    color: #8ab4f8;
    text-shadow: 0 0 5px #8ab4f8aa;
  }
  .form-input {
  background-color: #505156;
  border: none;
  border-radius: 8px;
  padding: 10px 15px;
  font-size: 1rem;
  color: #eee;
  outline: none;
  cursor: not-allowed; /* rõ ràng là không thể sửa */
  user-select: text;
}
  .form-grid {
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 25px 40px;
  }

  .form-label {
    font-weight: 600;
    font-size: 1.1rem;
    color: #a0a4af;
    align-self: center;
    letter-spacing: 0.02em;
    user-select: none;
  }

  /* Responsive */
  @media (max-width: 620px) {
    .form-grid {
      grid-template-columns: 1fr;
    }
    .form-label {
      margin-bottom: 6px;
    }
  }



</style>
<div class="admin-form">
  <div class="admin-title">Thông tin Quản trị viên</div>
  <div class="form-grid">
    <label class="form-label" for="admin-id">ID:</label>
    <input class="form-input" type="text" id="admin-id" value="<?= htmlspecialchars($adminDTO->id ?? 'Chưa có') ?>" readonly />

    <label class="form-label" for="admin-name">Tên:</label>
    <input class="form-input" type="text" id="admin-name" value="<?= htmlspecialchars($adminDTO->name ?? 'Chưa có') ?>" readonly />

    <label class="form-label" for="admin-email">Email:</label>
    <input class="form-input" type="email" id="admin-email" value="<?= htmlspecialchars($adminDTO->email ?? 'Chưa có') ?>" readonly />

    <label class="form-label" for="admin-position">Chức vụ:</label>
    <input class="form-input" type="text" id="admin-position" value="<?= 
    htmlspecialchars($adminDTO->position?"Quản trị viên":"Nhân viên") ?>" readonly />

    <label class="form-label" for="admin-created">Ngày tạo:</label>
    <input class="form-input" type="text" id="admin-created" value="<?= htmlspecialchars($adminDTO->created_at ?? '---') ?>" readonly />

    <label class="form-label" for="admin-updated">Cập nhật gần nhất:</label>
    <input class="form-input" type="text" id="admin-updated" value="<?= htmlspecialchars($adminDTO->updated_at ?? '---') ?>" readonly />
  </div>
</div>
