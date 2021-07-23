<?php
    class DBManager {
        function getConnection() {
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $conn = new PDO("mysql:host=$servername;dbname=myweb", $username, $password);
            //echo "Connect success";
    
            return $conn;
        }
    }
?>