<?php
    class database_sever{
        private $host = 'localhost';
        private $db_name;
        private $username = 'root';
        private $password = '';
        public $conn = null;
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
        public function view_table($string_query,$param=[]){
            try{
                $this->stmt = $this->conn->prepare($string_query);
                $this->stmt->execute($param);    
                return $this->stmt->fetchAll();
            } catch(PDOException $e) {
                echo "Lỗi: " . $e->getMessage();
            }
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
            if($this->conn){
            
            $this->conn = null;}
            #self::$instance = null
        }
    
        public function create_tables(array $table_queries) {
            try {
                $this->conn->beginTransaction();
                
                foreach ($table_queries as $query) {
                    $this->conn->exec($query);
                }
                
                $this->conn->commit();
                return true;
            } catch(PDOException $e) {
                $this->conn->rollBack();
                error_log("Table Creation Error: " . $e->getMessage());
                throw new Exception("Table creation failed: " . $e->getMessage());
            }
        }
        public function update_table($sql, $params = []) {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        }
    
        public function delete_table($sql, $params = []) {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        }
    
        public function lastInsertId() {
            return $this->conn->lastInsertId();
        }
    } 

?>