<?php

interface ClienteRepository
{
    public function criarCliente($nome, $telefone, $email);
    public function deletarCliente(Cliente $cliente);
    public function alterarCliente(Cliente $cliente);
    public function salvarCliente(Cliente $cliente);
    public function buscaClientePorNome(Cliente $cliente);
    public function buscaTodosClientes();
    public function verificaEmail($email);
}
