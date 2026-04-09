<?php 

class database {
    private $host = "localhost";
    private $dbname = "life_track";
    private $dbuser = "root";
    private $dbpass = "";
    private $pdo;

    public function __construct() {
        try {
            $this-> pdo - new pdo ("mysql:host=$this->host dbname=$this->dbname;charset=utf8mb4", $this->dbuser, this->dbpass);
            $this-> pdo -> setAtribute(pdo ::ATTR_Mode, pdo ::ERRMODE_EXCEPTION);
            $this-> pdo -> setAtribute(pdo ::ATTR_DEFAULT_FETCH_MODE, pdo ::FETCH_ASSOC);
        } catch (pdoexception $e ) {
            die("koneksi gagal:" . $e->getmassage());
        }
    }
    
    public function getpdo() {
        return $this->pdo;
    }

    public function __destruct() {
        this->pdo = null;
    }
}
?>