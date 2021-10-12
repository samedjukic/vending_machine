<?php
class Product{
  
    // database connection and table name
    private $conn;
    private $table_name = "product";
  
    // object properties
    public $id;
    public $productName;
    public $amountAvailable;
    public $cost;
    public $sellerId;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    
    // read products
    function read(){
        
        // select all query
        $query = "SELECT
                id, productName, cost, amountAvailable, sellerId
            FROM
                " . $this->table_name;
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // create product
    function create(){
        
        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                productName=:productName, cost=:cost, amountAvailable=:amountAvailable, sellerId=:sellerId";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->productName));
        $this->price=htmlspecialchars(strip_tags($this->cost));
        $this->description=htmlspecialchars(strip_tags($this->amountAvailable));
        $this->category_id=htmlspecialchars(strip_tags($this->sellerId));
        
        // bind values
        $stmt->bindParam(":productName", $this->productName);
        $stmt->bindParam(":cost", $this->cost);
        $stmt->bindParam(":amountAvailable", $this->amountAvailable);
        $stmt->bindParam(":sellerId", $this->sellerId);
        
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
                id, productName, cost, amountAvailable, sellerId
            FROM
                " . $this->table_name . " 
            WHERE
                id = ?
            LIMIT
                0,1";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);
        
        // execute query
        $stmt->execute();
        
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // set values to object properties
        $this->productName = $row['productName'];
        $this->cost = $row['cost'];
        $this->amountAvailable = $row['amountAvailable'];
        $this->sellerId = $row['sellerId'];
    }
    
    // update the product
    function update(){
        
        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                productName = :productName,
                cost = :cost,
                amountAvailable = :amountAvailable,
                sellerId = :sellerId
            WHERE
                id = :id";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->productName));
        $this->price=htmlspecialchars(strip_tags($this->cost));
        $this->description=htmlspecialchars(strip_tags($this->amountAvailable));
        $this->category_id=htmlspecialchars(strip_tags($this->sellerId));
        $this->id=htmlspecialchars(strip_tags($this->id));
        
        // bind new values
        $stmt->bindParam(':productName', $this->productName);
        $stmt->bindParam(':cost', $this->cost);
        $stmt->bindParam(':amountAvailable', $this->amountAvailable);
        $stmt->bindParam(':sellerId', $this->sellerId);
        $stmt->bindParam(':id', $this->id);
        
        // execute the query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
    
}
?>