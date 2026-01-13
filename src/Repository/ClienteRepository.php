<?php

interface ClienteRepository
{
    public function criarCliente(Cliente $cliente);
    public function deletarCliente(Cliente $cliente);
    public function alterarCliente(Cliente $cliente);
    public function salvarCliente(Cliente $cliente);
    public function buscaClientePorNome(Cliente $cliente);
    public function buscaTodosClientes();
}
