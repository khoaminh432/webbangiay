<?php
require_once __DIR__ . '/../DTO/ProductDTO.php';
require_once __DIR__ . '/../database/database_sever.php';

class ProductDao
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new database_sever();
        $this->conn = $this->db->conn;
    }
    
    /**
     * Lấy tất cả sản phẩm (tùy chọn include inactive)
     */
    public function view_all($includeInactive = false): array
    {
        $sql = "SELECT * FROM products";
        if (!$includeInactive) {
            $sql .= " WHERE is_active = TRUE";
        }

        $rows = $this->db->view_table($sql);
        return array_map(fn($r) => new ProductDTO($r), $rows);
    }
    /**
     * Sinh ID kế tiếp (liên tục từ 1)
     */
    public function generateNextId(): int
    {
        $sql = "SELECT id FROM products ORDER BY id ASC";
        $rows = $this->db->view_table($sql);

        $expected = 1;
        foreach ($rows as $r) {
            if ((int)$r['id'] !== $expected) {
                return $expected;
            }
            $expected++;
        }
        return $expected;
    }

    /**
     * Lấy sản phẩm theo ID
     */
    public function get_by_id(int $id): ?ProductDTO
    {
        $sql = <<<SQL
        SELECT p.*, pi.image_url
        FROM products p
        LEFT JOIN product_images pi
          ON pi.id_product = p.id AND pi.is_primary = 1
        WHERE p.id = :id
        SQL;
        $row = $this->db->view_table($sql, ['id' => $id]);
        return $row ? new ProductDTO($row[0]) : null;
    }

    /**
     * Lấy nhiều sản phẩm theo mảng ID
     */
    public function get_by_ids(array $ids): array
    {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = <<<SQL
SELECT p.id, p.name, p.price, p.quantity, pi.image_url
FROM products p
LEFT JOIN product_images pi
  ON pi.id_product = p.id AND pi.is_primary = 1
WHERE p.id IN ($placeholders)
SQL;
        $rows = $this->db->view_table($sql, $ids);
        return array_map(fn($r) => new ProductDTO($r), $rows);
    }

    /**
     * Tìm kiếm theo tên hoặc mô tả
     */
    public function search(string $keyword, bool $includeInactive = false): array
    {
        $sql = "SELECT * FROM products WHERE (name LIKE :kw OR description LIKE :kw)";
        if (!$includeInactive) {
            $sql .= " AND is_active = TRUE";
        }
        $params = ['kw' => "%{$keyword}%"];
        $rows = $this->db->view_table($sql, $params);
        return array_map(fn($r) => new ProductDTO($r), $rows);
    }

    /**
     * Thêm mới sản phẩm
     */
    public function get_by_voucher(int $voucherId, bool $includeInactive = false): array
    {
        $sql = "SELECT p.*, pi.image_url
                FROM products p
                LEFT JOIN product_images pi ON pi.id_product = p.id AND pi.is_primary = 1
                WHERE p.id_voucher = :voucherId";
        if (!$includeInactive) {
            $sql .= " AND p.is_active = TRUE";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':voucherId', $voucherId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => new ProductDTO($r), $rows);
    }

    public function insert(ProductDTO $product){
            $sql = "INSERT INTO products (id,name, quantity, description, price, weight, id_voucher, id_type_product, id_admin, id_supplier, is_active, image_url) 
                VALUES (:id,:name, :quantity, :description, :price, :weight, :id_voucher, :id_type_product, :id_admin, :id_supplier, :is_active, :image_url)";

        $params = [
            "id" =>$this->generateNextId(),
            'name' => $product->name,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'price' => $product->price,
            'weight' => $product->weight,
            'id_voucher' => $product->id_voucher,
            'id_type_product' => $product->id_type_product,
            'id_admin' => $product->id_admin,
            'id_supplier' => $product->id_supplier,
            'is_active' => $product->is_active,
            "image_url" =>$product->image_url
        ];
        
        try {
            $this->db->insert_table($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("ProductDao Insert Error: " . $e->getMessage());
            return false;
        }
    }   

    // Cập nhật thông tin sản phẩm


    /**
     * Cập nhật sản phẩm
     */
    public function update(ProductDTO $product): bool
    {
        $sql = <<<SQL
UPDATE products SET
  name = :name,
  quantity = :quantity,
  description = :description,
  price = :price,
  weight = :weight,
  id_voucher = :id_voucher,
  id_type_product = :id_type_product,
  id_admin = :id_admin,
  id_supplier = :id_supplier,
  is_active = :is_active,
  image_url = :image_url
WHERE id = :id
SQL;
        return (bool)$this->db->update_table($sql, (array)$product);
    }

    /**
     * Xóa sản phẩm
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        return (bool)$this->db->delete_table($sql, ['id' => $id]);
    }

    /**
     * Cập nhật số lượng
     */
    public function update_quantity(int $id, int $qty): bool
    {
        $sql = "UPDATE products SET quantity = :qty WHERE id = :id";
        return (bool)$this->db->update_table($sql, ['id' => $id, 'qty' => $qty]);
    }

    /**
     * Cập nhật trạng thái active/inactive
     */
    public function update_active(int $id, bool $active): bool
    {
        $sql = "UPDATE products SET is_active = :active WHERE id = :id";
        return (bool)$this->db->update_table($sql, ['id' => $id, 'active' => $active]);
    }

    /**
     * Đếm tổng sản phẩm
     */
    public function count_all(bool $includeInactive = false): int
    {
        $sql = "SELECT COUNT(*) AS total FROM products" .
               (!$includeInactive ? " WHERE is_active = TRUE" : "");
        $row = $this->db->view_table($sql);
        return (int)($row[0]['total'] ?? 0);
    }

    /**
     * Lấy danh sách kèm chi tiết, phân trang
     */

    public function get_products_paginated(int $offset, int $limit, bool $includeInactive = false): array
    {
        $sql = <<<SQL
SELECT p.*, tp.name AS type_name, s.name AS supplier_name,
       pi.image_url
FROM products p
LEFT JOIN type_product tp ON p.id_type_product = tp.id
LEFT JOIN supplier s ON p.id_supplier = s.id
LEFT JOIN product_images pi ON pi.id_product = p.id AND pi.is_primary = 1
SQL;
        if (!$includeInactive) {
            $sql .= " WHERE p.is_active = 1";
        }
        $sql .= " GROUP BY p.id LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy sản phẩm mới nhất
     */
    public function get_newest_products(int $limit = 5): array
    {
        $sql = "SELECT * FROM products WHERE is_active = TRUE ORDER BY id DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => new ProductDTO((array)$r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getConnection()
    {
        return $this->conn;
    }
    public function check_billproduct($id_product){
        require_once __DIR__."/BillProductDao.php";
        $temp = $table_billproducts->get_by_product($id_product);
        return empty($temp);
    }
    public function exists_by_dto(ProductDTO $dto) {
    $conn = $this->db->getConnection();
    $sql = "SELECT COUNT(*) FROM products WHERE name = :name AND  id_type_product= :id_type_product";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $dto->name);
    $stmt->bindParam(':id_type_product', $dto->id_type_product);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0;
}
    /**
 * Lấy sản phẩm theo loại sản phẩm
 */
public function get_by_type(int $typeId, bool $includeInactive = false): array
{
    $sql = <<<SQL
    SELECT p.*
    FROM products p
    WHERE p.id_type_product = :typeId
    SQL;
    
    if (!$includeInactive) {
        $sql .= " AND p.is_active = TRUE";
    }
    
    $sql .= " ORDER BY p.id DESC";
    
    $rows = $this->db->view_table($sql, ['typeId' => $typeId]);
    return array_map(fn($r) => new ProductDTO($r), $rows);
}
 public function get_products_with_details() {
        $sql = "SELECT p.*, tp.name as type_name, s.name as supplier_name,
                pi.image_url
                FROM products p
                LEFT JOIN type_product tp ON p.id_type_product = tp.id
                LEFT JOIN supplier s ON p.id_supplier = s.id
                LEFT JOIN product_images pi ON p.id = pi.id_product AND pi.is_primary = 1
                WHERE p.is_active = 1
                GROUP BY p.id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }   
    public function count_all_products(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE is_active = 1";
        $row = $this->db->view_table($sql);
        return (int)($row[0]['total'] ?? 0);
    }
    public function countFilteredProducts($query, $category, $min_price, $max_price) {
    $sql = "SELECT COUNT(*) as total FROM products WHERE 1=1";
    $params = array();
    
    if (!empty($query)) {
        $sql .= " AND name LIKE ?";
        $params[] = "%$query%";
    }
    if (!empty($category)) {
        $sql .= " AND id_type_product = ?";
        $params[] = $category;
    }
    if (!empty($min_price)) {
        $sql .= " AND price >= ?";
        $params[] = $min_price;
    }
    if (!empty($max_price)) {
        $sql .= " AND price <= ?";
        $params[] = $max_price;
    }
    
    $result = $this->db->view_table($sql, $params);
    return (int)($result[0]['total'] ?? 0);
}

    public function getFilteredProductsWithPagination($query, $category, $min_price, $max_price, $offset, $limit) {
        $sql = "SELECT *, image_url FROM products WHERE 1=1";
        $params = array();
        
        if (!empty($query)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$query%";
        }
        if (!empty($category)) {
            $sql .= " AND id_type_product = ?";
            $params[] = $category;
        }
        if (!empty($min_price)) {
            $sql .= " AND price >= ?";
            $params[] = $min_price;
        }
        if (!empty($max_price)) {
            $sql .= " AND price <= ?";
            $params[] = $max_price;
        }
        
        $sql .= " LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $limit;
        
        return $this->db->view_table($sql, $params);
    }
    }

// khởi tạo sẵn nếu cần
// $table_products = new ProductDao();
?>

<?php $table_products = new ProductDao(); ?>