<?php
    function connectDB(){
        $host = 'aws-1-sa-east-1.pooler.supabase.com';
        $port = '5432';
        $dbname = 'postgres';
        $user = 'postgres.kjdfnnxekvvigylhxplh';
        $password = 'cB#14?6>9memories';

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]); 
            return $pdo;
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
        
    }

    function closeDB($conn){
        $conn = null;
    }
?>