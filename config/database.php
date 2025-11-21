<?php

class Database {



    private $host = "localhost";

    private $db_name = "arc";

    // private $username = "daniyal";
    private $username;

    // private $password = "03172746242dA@";
    private $password;



    // private $username = "daniyal";
    // private $username = "root";

    // private $password = "03172746242dA@";
    // private $password = "";

public function __construct() {
        // Get the server's host (domain or localhost)
        $checkHost = $_SERVER['SERVER_NAME'];  // Retrieve the server name

        // Conditionally set username and password based on the environment
        if (strpos($checkHost, 'localhost') !== false || strpos($checkHost, '127.0.0.1') !== false) {
            $this->username = "root";  // Local environment
            $this->password = "";      // Local environment (no password needed)
        } else {
            $this->username = "daniyal";  // Production environment
            $this->password = "03172746242dA@";  // Production password
        }
    }


    public $conn;



    public function getConnection() {

        $this->conn = null;

        try {

            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

            $this->conn->exec("set names utf8");

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {

            echo "Connection error: " . $exception->getMessage();

        }

        return $this->conn;

    }

}

?>