<?php

interface ClienteRepository
{
    public function criarCliente($nome, $telefone, $email);
    public function deletarCliente($id);
    public function alterarCliente($id, $nome, $telefone, $email);
    public function salvarCliente($id, $nome, $telefone, $email);
    public function buscaClientePorNome(Cliente $cliente);
    public function buscaClientePorId($id);
    public function buscaTodosClientes();
    public function verificaEmail($email);
    public function validarTelefoneContato($telefone);
}
