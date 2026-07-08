<?php

class usuario {

    private int    $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id       = (int) ($dados['id']       ?? 0);
        $this->nome     =        $dados['nome']      ?? '';
        $this->email    =        $dados['email']     ?? '';
        $this->senha    =        $dados['senha']     ?? '';
        $this->criadoEm =        $dados['criado_em'] ?? '';
    }

    public function getId():       int    { return $this->id; }
    public function getNome():     string { return $this->nome; }
    public function getEmail():    string { return $this->email;}
    public function getSenha():    string { return $this->senha; }
    public function getCriadoEm(): string { return $this->criadoEm; }
}