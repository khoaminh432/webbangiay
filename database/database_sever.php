<?php
    class database_sever{
        private $host = 'localhost';
        private $db_name;
        private $username = 'root';
        private $password = '';
        private $conn = null;
        public $stmt=null;

        public function __construct($db_name="bangiay"){
            
            $this->db_name = $db_name;
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
        }
        public function view_table($string_query){
            $this->stmt = $this->conn->prepare($string_query);
            $this->stmt->execute();    
            return $this->stmt->fetchAll();
        }   
        # $sql = INSERT INTO users (username, email, password) VALUES (:username, :email, :password)
        # $param = ['username' => 'john_doe','email' => 'john@example.com','password' => password_hash('secure123', PASSWORD_DEFAULT)];
    
        public function insert_table($string_query,$param){
            try {
            $sql = $string_query;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($param);
            
            echo "Thêm dữ liệu thành công, ID: " . $this->conn->lastInsertId();
        } catch(PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
        }
        }
        public function close(){
            $this->conn = null;
            #self::$instance = null
        }
    } 
?>