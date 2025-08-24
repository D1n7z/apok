<?php
    function connectDB(){
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASS');

        if (!$host || !$user || !$password) {
            error_log("Erro Crítico: As variáveis de ambiente do banco de dados não estão configuradas.");
            die("Erro de configuração do servidor. Por favor, tente novamente mais tarde.");
        }

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]); 
            return $pdo;
        } catch (PDOException $e) {
            error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
            die("Ocorreu um erro ao conectar com o banco de dados.");
        }
    }

    function closeDB($conn){
        $conn = null;
    }
?>