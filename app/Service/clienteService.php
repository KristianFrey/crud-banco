<?php

namespace App\Service;

use Exception;
use App\Models\Cliente;
use App\Interfaces\ClienteRepository;

class ClienteService
{
    public function __construct(
        private ClienteRepository $repository
    ) {}

    public function cadastrar($email, $nome, $telefone)
    {
        if ($this->repository->verificaEmail($email)) {
            throw new Exception("Email duplicado");
        }

        $cliente = new Cliente();
        $cliente->nome = $nome;
        $cliente->email = $email;
        $cliente->telefone = $telefone;

        $this->repository->salvarCliente($cliente);
    }

    public function deletar(int $id): void
    {
        if ($id <= 0) {
            throw new Exception("Id inválido");
        }

        $this->repository->deletarCliente($id);
    }

    function buscarClienteId($id)
    {
        if ($id >= 0) {
            $this->buscarClienteId($id);
        } else {
            throw new Exception("Cliente sem Id válido");
        }
    }

    function buscarClientes()
    {
        try {
            $this->repository->buscaTodosClientes();
        } catch (\Throwable $e) {
            echo "Erro ao buscar clientes.";
        }
    }
}
