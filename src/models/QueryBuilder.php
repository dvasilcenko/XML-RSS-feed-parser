<?php
namespace Parser\models;

use \PDO;
class QueryBuilder
{
    public $pdo;
    public $table;
    
    public function __construct(){
        try {
            $config = file_get_contents('src/config');
            $config = json_decode($config);
            $this->pdo = new PDO('mysql:host=' . $config->dbhost .';dbname=' . $config->dbname,
                                 $config->dbuser, $config->dbpass);
        }
        // SPL Feature!!!
        catch(\Exception $e) {
            die( 'Please check your database config at parser/src/config' );
        }
    }
    
    public function findBy( $attributes ) {
        $value = reset($attributes);
        $column = key($attributes);
        
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE $column = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findOneBy( $attributes ) {
        $value = reset($attributes);
        $column = key($attributes);
        
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE $column = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function findByMostRecent( $value1, $value2  ) {
        $value = reset($value1);
        $column = key($value1);
        
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE $column = :value ORDER BY $value2 DESC LIMIT 1");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countBy( $attributes ) {
        $value = reset($attributes);
        $column = key($attributes);
        
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE $column = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    public function selectDistinctColumn( $column ) {
        $stmt = $this->pdo->prepare("SELECT DISTINCT $column FROM {$this->table}");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteBy( $attributes ) {
        $value = reset($attributes);
        $column = key($attributes);
        
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE $column = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }
    
    public function insertIntoBy( $attributes ) {
        $column = implode(", ", array_keys($attributes));
        $value = ":" . implode(", :", array_keys($attributes));
        
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($column) VALUES ($value)");

        foreach ($attributes as $key => $attribute)
        {
            $stmt->bindValue($key, $attribute);
        }
        
        $stmt->execute();
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}