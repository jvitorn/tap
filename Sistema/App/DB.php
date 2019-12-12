<?php
    namespace App;
    
    class DB{
        
        static public function connectDB(){
           
            try{
				$con = new \PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PW);
				$con->exec("set names utf8");
				return $con;
			}catch(\PDOException $erro){
				return $erro->getMessage();
			}
        }
    }