-- Xóa bảng product_size_color trước vì có khóa ngoại tới bảng size
DROP TABLE IF EXISTS product_size_color;

-- Xóa bảng size cũ
DROP TABLE IF EXISTS sizes;

-- Tạo lại bảng size với các size 37-44
CREATE TABLE sizes (
  id INT NOT NULL AUTO_INCREMENT,
  size_number INT NOT NULL COMMENT 'Kích thước giày (37-44)',
  description VARCHAR(50) COMMENT 'Mô tả size',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (size_number)
);
DROP TABLE IF EXISTS colors;
CREATE TABLE colors (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL COMMENT 'Tên màu (ví dụ: Đen, Trắng, Đỏ)',
  hex_code VARCHAR(7) COMMENT 'Mã màu HEX (ví dụ: #000000)',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (name)
);
-- Thêm dữ liệu size mới
INSERT INTO sizes (size_number, description) VALUES
(37, 'Size 37 - Nữ'),
(38, 'Size 38 - Nữ/Nam nhỏ'),
(39, 'Size 39 - Nam'),
(40, 'Size 40 - Nam'),
(41, 'Size 41 - Nam lớn'),
(42, 'Size 42 - Nam lớn'),
(43, 'Size 43 - Nam rất lớn'),
(44, 'Size 44 - Nam rất lớn');
INSERT INTO colors (name, hex_code) VALUES
('Đen', '#000000'),
('Trắng', '#FFFFFF'),
('Đỏ', '#FF0000'),
('Xanh Navy', '#000080'),
('Xám', '#808080'),
('Xanh lá', '#008000'),
('Xanh dương', '#0000FF');
-- Tạo lại bảng product_size_color
CREATE TABLE product_size_color (
  id INT NOT NULL AUTO_INCREMENT,
  id_product INT NOT NULL,
  id_size INT NOT NULL,
  id_color INT NOT NULL,
  quantity INT NOT NULL DEFAULT 0 COMMENT 'Số lượng tồn kho cho từng size và màu',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_product) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (id_size) REFERENCES sizes(id) ON DELETE CASCADE,
  FOREIGN KEY (id_color) REFERENCES colors(id) ON DELETE CASCADE,
  UNIQUE KEY (id_product, id_size, id_color) COMMENT 'Mỗi sản phẩm chỉ có 1 bản ghi cho mỗi cặp size và màu'
);
DELIMITER $$
CREATE TRIGGER trg_update_product_quantity_after_insert
AFTER INSERT ON product_size_color
FOR EACH ROW
BEGIN
  UPDATE products
  SET quantity = (
    SELECT SUM(quantity)
    FROM product_size_color
    WHERE id_product = NEW.id_product
  )
  WHERE id = NEW.id_product;
END$$
DELIMITER ;
-- Thêm lại dữ liệu product_size_color với size mới
INSERT INTO product_size_color (id_product, id_size, id_color, quantity) VALUES
-- Sản phẩm 1 (Nike Air Max) - size 39-43
(1, 3, 1, 10),
(1, 3, 2, 8),
(1, 3, 3, 5),
(1, 4, 1, 12),
(1, 4, 2, 7),
(1, 5, 1, 8),

-- Sản phẩm 2 (Adidas Ultraboost) - size 38-42
(2, 2, 2, 6),
(2, 3, 2, 10),
(2, 3, 4, 7),
(2, 4, 2, 9),
(2, 4, 4, 5),
(2, 5, 2, 8),

-- Sản phẩm 3 (Puma Future) - size 39-42
(3, 3, 1, 5),
(3, 3, 3, 7),
(3, 4, 1, 6),
(3, 4, 3, 4),
(3, 5, 1, 8),
(3, 5, 3, 5),

-- Các sản phẩm khác
(4, 4, 1, 3),
(4, 4, 2, 4),
(4, 5, 1, 5),
(5, 2, 5, 10),
(5, 3, 5, 12),
(6, 4, 6, 8),
(7, 1, 7, 15),
(8, 3, 1, 2);

DELIMITER $$
CREATE TRIGGER trg_update_product_quantity_after_update
AFTER UPDATE ON product_size_color
FOR EACH ROW
BEGIN
  -- Nếu đổi sang sản phẩm khác, cập nhật cả hai
  IF NEW.id_product != OLD.id_product THEN
    UPDATE products
    SET quantity = (
      SELECT SUM(quantity)
      FROM product_size_color
      WHERE id_product = OLD.id_product
    )
    WHERE id = OLD.id_product;

    UPDATE products
    SET quantity = (
      SELECT SUM(quantity)
      FROM product_size_color
      WHERE id_product = NEW.id_product
    )
    WHERE id = NEW.id_product;
  ELSE
    -- Cập nhật lại sản phẩm hiện tại
    UPDATE products
    SET quantity = (
      SELECT SUM(quantity)
      FROM product_size_color
      WHERE id_product = NEW.id_product
    )
    WHERE id = NEW.id_product;
  END IF;
END$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER trg_update_product_quantity_after_delete
AFTER DELETE ON product_size_color
FOR EACH ROW
BEGIN
  UPDATE products
  SET quantity = (
    SELECT IFNULL(SUM(quantity), 0)
    FROM product_size_color
    WHERE id_product = OLD.id_product
  )
  WHERE id = OLD.id_product;
END$$
DELIMITER ;
