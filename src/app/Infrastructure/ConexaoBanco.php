<?php

namespace App\Infrastructure;

use PDO;
use PDOException;
use Exception;

class ConexaoBanco
{

    public static function conectarBanco(): PDO
    {
        try {
            $driver = "mysql:";
            $dns = "dbname=dbLogin;host=localhost;";
            $usuario = "root";
            $senha = "cli000";
            $codificacao = "charset=utf8mb4";

            $pdo = new PDO($driver . $dns, $usuario, $senha);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Erro genérico" . $e->getMessage());
        }
    }
}
