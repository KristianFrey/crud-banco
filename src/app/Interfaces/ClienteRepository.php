<?php

namespace App\Interfaces;

use App\Models\Cliente;

interface ClienteRepository
{
    public function criarCliente($nome, $telefone, $email);
    public function deletarCliente($id);
    public function alterarCliente($id, $nome, $telefone, $email);
    public function salvarCliente(Cliente $cliente);
    public function buscaClientePorNome($nome);
    public function buscaClientePorId($id);
    public function buscaTodosClientes();
    public function verificaEmail($email);
    public function validarTelefoneContato($telefone);
}
