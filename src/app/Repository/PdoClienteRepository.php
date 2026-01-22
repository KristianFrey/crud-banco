<?php

namespace App\Repository;

use Pdo;
use App\Infrastructure\ConexaoBanco;
use Exception;
use App\Interfaces\ClienteRepository;
use App\Models\Cliente;

class PdoClienteRepository implements ClienteRepository
{

    public PDO $conexao;

    public function __construct()
    {
        $this->conexao = conexaoBanco::conectarBanco();
    }

    public function criarCliente($nome, $telefone, $email)
    {
        try {
            $sql = "INSERT INTO cliente (
                    nome, 
                    telefone, 
                    email) 
                VALUES (
                    :nomeCliente, 
                    :telefoneCliente, 
                    :emailCliente)";

            $query = $this->conexao->prepare($sql);
            $query->bindParam(':nomeCliente', $nome, PDO::PARAM_STR);
            $query->bindParam(':telefoneCliente', $telefone, PDO::PARAM_STR);
            $query->bindParam(':emailCliente', $email, PDO::PARAM_STR);
            return $query->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function deletarCliente($id)
    {
        try {
            $sql = "DELETE FROM cliente WHERE id = :idCliente";
            $query = $this->conexao->prepare($sql);
            $query->bindParam(":idCliente", $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function alterarCliente($id, $nome, $telefone, $email)
    {
        $sql = "UPDATE cliente 
                SET nome = :nomeCliente, 
                telefone = :telefoneCliente, 
                email = :emailCliente
                WHERE 
                id = :idCliente";

        $query = $this->conexao->prepare($sql);
        $query->bindParam(":nomeCliente", $nome, PDO::PARAM_STR);
        $query->bindParam(":telefoneCliente", $telefone, PDO::PARAM_STR);
        $query->bindParam(":emailCliente", $email, PDO::PARAM_STR);
        $query->bindParam(":idCliente", $id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function salvarCliente(Cliente $cliente)
    {
        $this->conexao->beginTransaction();
        try {
            if ($this->validarTelefoneContato($cliente->telefone))
                if (!empty($id)) {
                    $this->alterarCliente($cliente->id, $cliente->nome, $cliente->telefone, $cliente->email);
                } else {
                    $this->criarCliente($cliente->nome, $cliente->telefone, $cliente->email);
                }
            else {
                throw new Exception("Telefone inválido.");
            }
            $this->conexao->commit();
        } catch (Exception $e) {
            $this->conexao->rollBack();
            throw $e;
        }
    }

    public function buscaClientePorNome($nome)
    {
        $nomeCliente = "%" . $nome . "%";
        $sql = "SELECT * 
                FROM cliente 
                WHERE 
                    nome LIKE :nomeCliente";

        $query =  $this->conexao->prepare($sql);
        $query->bindParam(":nomeCliente", $nomeCliente);
        $query->execute();
        return $this->hidratarDados($query);
    }

    public function buscaClientePorId($id)
    {
        $sql = "SELECT * 
                FROM cliente 
                WHERE 
                    id = :idCliente";

        $query =  $this->conexao->prepare($sql);
        $query->bindParam(":idCliente", $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function buscaTodosClientes()
    {
        $sql = "SELECT * 
                FROM cliente";
        $query = $this->conexao->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
        //return $this->hidratarDados($query);
    }

    public function hidratarDados($query)
    {
        $listaCliente = $query->fetchAll(PDO::FETCH_ASSOC);
        $clienteTratado = [];

        foreach ($listaCliente as $cliente) {
            $clienteTratado[] = new Cliente(
                $cliente['id'],
                $cliente['nome'],
                $cliente['telefone'],
                $cliente['email'],
                null
            );
        }
        return $clienteTratado;
    }

    public function verificaEmail($email)
    {
        $sql = "SELECT * FROM cliente WHERE email = :emailCliente";
        $query = $this->conexao->prepare($sql);
        $query->bindParam(":emailCliente", $email);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validarTelefoneContato($telefone)
    {
        $regex = '/^(\(?\d{2}\)?\s?)?(\d{4,5})-?\d{4}$/';
        return preg_match($regex, $telefone) === 1;
    }
}
