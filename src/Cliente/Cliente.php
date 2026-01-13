<?php

class Cliente
{
    public int $id;
    public string $nome;
    public string $telefone;
    public string $email;

    public function __construct($id, $nome, $telefone, $email)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
    }
}
