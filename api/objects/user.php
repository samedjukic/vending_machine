<?php
class User{
  
    private $conn;
    private $table_name = "user";
  
    public $id;
    public $username;
    public $password;
    public $deposit;
    public $role;
  
    public function __construct($db){
        $this->conn = $db;
    }
    
    
    function read(){
        
        $query = "SELECT id, username, password, deposit, role
            FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function create(){
        
        $query = "INSERT INTO " . $this->table_name . "
            SET
                username=:username, password=:password, deposit=:deposit, role=:role";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->deposit=htmlspecialchars(strip_tags($this->deposit));
        $this->role=htmlspecialchars(strip_tags($this->role));
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":deposit", $this->deposit);
        $stmt->bindParam(":role", $this->role);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
        
    }
    
    function readOne(){
        $query = "SELECT id, username, password, deposit, role
            FROM " . $this->table_name . " 
            WHERE
                username = ? AND password = ?
            LIMIT
                0,1";
        
        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->password);
        
        $stmt->execute();
        
        return $stmt;
    }
    
    function readOneById(){
        $query = "SELECT id, username, password, deposit, role
            FROM " . $this->table_name . "
            WHERE
                id = ? 
            LIMIT
                0,1";
        
        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(1, $this->id);
        
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->username = $row['username'];
        $this->password = $row['password'];
        $this->deposit = $row['deposit'];
        $this->role = $row['role'];
    }
    
    function update(){
        
        $query = "UPDATE " . $this->table_name . "
            SET
                username = :username,
                password = :password,
                deposit = :deposit,
                role = :role
            WHERE
                id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->deposit=htmlspecialchars(strip_tags($this->deposit));
        $this->role=htmlspecialchars(strip_tags($this->role));
        $this->id=htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':deposit', $this->deposit);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
    
    function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id=htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    
    
    
}
?>