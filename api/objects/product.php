<?php
class Product{
  
    private $conn;
    private $table_name = "product";
  
    public $id;
    public $productName;
    public $amountAvailable;
    public $cost;
    public $sellerId;
  
    public function __construct($db){
        $this->conn = $db;
    }
    
    
    function read(){
        
        $query = "SELECT id, productName, cost, amountAvailable, sellerId FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    function create(){
        
        $query = "INSERT INTO " . $this->table_name . " SET
                productName=:productName, cost=:cost, amountAvailable=:amountAvailable, sellerId=:sellerId";
        
        $stmt = $this->conn->prepare($query);
        
        $this->name=htmlspecialchars(strip_tags($this->productName));
        $this->price=htmlspecialchars(strip_tags($this->cost));
        $this->description=htmlspecialchars(strip_tags($this->amountAvailable));
        $this->category_id=htmlspecialchars(strip_tags($this->sellerId));
        
        $stmt->bindParam(":productName", $this->productName);
        $stmt->bindParam(":cost", $this->cost);
        $stmt->bindParam(":amountAvailable", $this->amountAvailable);
        $stmt->bindParam(":sellerId", $this->sellerId);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
        
    }
    
    function readOne(){
        
        $query = "SELECT id, productName, cost, amountAvailable, sellerId
            FROM  " . $this->table_name . " 
            WHERE id = ?
            LIMIT 0,1";
        
        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(1, $this->id);
        
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->productName = $row['productName'];
        $this->cost = $row['cost'];
        $this->amountAvailable = $row['amountAvailable'];
        $this->sellerId = $row['sellerId'];
    }
    
    function update(){
        
        $query = "UPDATE " . $this->table_name . "
            SET
                productName = :productName,
                cost = :cost,
                amountAvailable = :amountAvailable,
                sellerId = :sellerId
            WHERE
                id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->name=htmlspecialchars(strip_tags($this->productName));
        $this->price=htmlspecialchars(strip_tags($this->cost));
        $this->description=htmlspecialchars(strip_tags($this->amountAvailable));
        $this->category_id=htmlspecialchars(strip_tags($this->sellerId));
        $this->id=htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(':productName', $this->productName);
        $stmt->bindParam(':cost', $this->cost);
        $stmt->bindParam(':amountAvailable', $this->amountAvailable);
        $stmt->bindParam(':sellerId', $this->sellerId);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
    
}
?>