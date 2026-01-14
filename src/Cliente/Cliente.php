<?php


class Cliente
{
    public null | int $id;
    public null |string $nome;
    public null |string $telefone;
    public null |string $email;
    public null | ClienteRepository $repository;

    public function __construct($clienteRepository)
    {
        $this->repository = $clienteRepository;
    }

    public function cadastrarCliente($email, $nome, $telefone)
    {
        if (count($this->repository->verificaEmail($email)) > 0) {
            throw new Exception("Email duplicado no banco. Você deve alterar o registro existente");
        } else {
            $this->repository->criarCliente($nome, $telefone, $email);
        }
    }
}
