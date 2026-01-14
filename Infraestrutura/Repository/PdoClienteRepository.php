<?php
require_once __DIR__ . '/../../src/Repository/ClienteRepository.php';
require_once __DIR__ . '/../../src/Cliente/Cliente.php';
require_once __DIR__ . '/../ConexaoBanco.php';

class PdoClienteRepository implements ClienteRepository
{

    public PDO $conexao;

    public function __construct()
    {
        $this->conexao = conexaoBanco::conectarBanco();
    }

    public function criarCliente(Cliente $cliente): bool
    {

        $sql = "INSERT INTO cliente (
                    nome, 
                    telefone, 
                    email) 
                VALUES (
                    :nomeCliente, 
                    :telefoneCliente, 
                    :emailCliente)";

        $query = $this->conexao->prepare($sql);
        $query->bindParam(':nomeCliente', $cliente->nome, PDO::PARAM_STR);
        $query->bindParam(':telefoneCliente', $cliente->telefone, PDO::PARAM_STR);
        $query->bindParam(':emailCliente', $cliente->email, PDO::PARAM_STR);

        return $query->execute();
    }

    public function deletarCliente(Cliente $cliente): bool
    {
        $sql = "DELETE FROM cliente WHERE id = :idCliente";
        $query = $this->conexao->prepare($sql);
        $query->bindParam(":idCliente", $cliente->id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function alterarCliente(Cliente $cliente): bool
    {
        $sql = "UPDATE cliente 
                SET nome = :nomeCliente, 
                telefone = :telefoneCliente, 
                email = :emailCliente
                WHERE 
                id = :idCliente";

        $query = $this->conexao->prepare($sql);
        $query->bindParam(":nomeCliente", $cliente->nome, PDO::PARAM_STR);
        $query->bindParam(":telefoneCliente", $cliente->telefone, PDO::PARAM_STR);
        $query->bindParam(":emailCliente", $cliente->email, PDO::PARAM_STR);
        $query->bindParam(":idCliente", $cliente->id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function salvarCliente(Cliente $cliente)
    {
        $this->conexao->beginTransaction();
        try {
            if (isset($cliente->id)) {
                $this->alterarCliente($cliente);
            } else {
                $this->criarCliente($cliente);
            }
            $this->conexao->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->conexao->rollBack();
        }
    }
    public function buscaClientePorNome(Cliente $cliente)
    {
        $nomeCliente = "%" . $cliente->nome . "%";
        $sql = "SELECT * 
                FROM cliente 
                WHERE 
                    nome LIKE :nomeCliente";

        $query =  $this->conexao->prepare($sql);
        $query->bindParam(":nomeCliente", $nomeCliente);
        $query->execute();
        return $this->hidratarDados($query);
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
        return $query->execute();
    }
}
