<?php


class Cliente
{
    public int $id;
    public string $nome;
    public string $telefone;
    public string $email;
    public null | ClienteRepository $repository;

    public function __construct($id, $nome, $telefone, $email, $clienteRepository)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->repository = $clienteRepository;
    }

    public function cadastrarCliente()
    {
        if (count($this->repository->verificaEmail($this->email)) > 0) {
            throw new Exception("Email duplicado no banco. Você deve alterar o registro existente");
        } else {
            $this->repository->criarCliente($this);
        }
    }
}
