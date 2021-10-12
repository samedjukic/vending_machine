<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "user";
  
    // object properties
    public $id;
    public $username;
    public $password;
    public $deposit;
    public $role;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    
    // read users
    function read(){
        
        // select all query
        $query = "SELECT 
                id, username, password, deposit, role
            FROM
                " . $this->table_name;
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // create user
    function create(){
        
        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                username=:username, password=:password, deposit=:deposit, role=:role";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->deposit=htmlspecialchars(strip_tags($this->deposit));
        $this->role=htmlspecialchars(strip_tags($this->role));
        
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":deposit", $this->deposit);
        $stmt->bindParam(":role", $this->role);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
        
    }
    
    // used when filling up the update product form
    function readOne(){
        // query to read single record
        $query = "SELECT
                id, username, password, deposit, role
            FROM
                " . $this->table_name . " 
            WHERE
                username = ? AND password = ?
            LIMIT
                0,1";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // bind id of product to be updated
        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->password);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
}
?>