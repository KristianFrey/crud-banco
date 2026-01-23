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

    public function cadastrar($id, $nome, $email, $telefone)
    {
        $cliente = new Cliente();
        if (isset($id)) {
            $cliente->id = $id;
        }
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
            return $this->repository->buscaClientePorId($id);
        } else {
            throw new Exception("Cliente sem Id válido");
        }
    }

    function buscarClientes()
    {
        try {
            return $this->repository->buscaTodosClientes();
        } catch (\Throwable $e) {
            echo "Erro ao buscar clientes.";
        }
    }
}
