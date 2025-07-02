<?php

class ExamDao {

    private $conn;

    /**
     * constructor of dao class
     */
    public function __construct(){
        try {
          /** TODO
           * List parameters such as servername, username, password, schema. Make sure to use appropriate port
           */

           $host='localhost';
           $username='root';
           $password='';
           $port=3306;
           $schema='2025_fall';



          /** TODO
           * Create new connection
           */
            $this->conn=new PDO(
                    "mysql:host=$host;dbname=$schema;charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

    /** TODO
     * Implement DAO method used to get customer information
     */
    public function get_customers(){
      $db = $this->conn->prepare("SELECT * FROM customers c");
      $db->execute();
      return $db->fetchAll();

    }

    /** TODO
     * Implement DAO method used to get customer meals
     */
    public function get_customer_meals($customer_id) {
            $db = $this->conn->prepare("select 
              m.created_at as meal_date, 
              f.name as food_name, 
              f.brand as food_brand 
            from meals m join foods f on m.food_id = f.id 
            where m.customer_id = $customer_id;");
            $db->execute();

            return $db->fetchAll();
    }

    /** TODO
     * Implement DAO method used to save customer data
     */
    public function add_customer($first_name, $last_name, $birth_date){
      
      $sql = "INSERT INTO customers (first_name, last_name, birth_date) VALUES (:first_name, :last_name, :birth_date)";
      $db = $this->conn->prepare($sql);
      $db->bindParam(':first_name', $first_name); 
      $db->bindParam(':last_name', $last_name); 
      $db->bindParam(':birth_date', $birth_date); 

      $db->execute();

      return (int)$this->conn->lastInsertId();

    }

    /** TODO
     * Implement DAO method used to get foods report
     */
    public function get_foods_report(){

    }
}
?>
