<?php

class Livro {

    private int    $id;
    private string $titulo;
    private string $descricao;
    private string $situacao;
    private int    $nota;
    private string $capa;
    private int    $IdAutor;
    private int    $IdCategoria;
    private int    $Idusuario;
    private int $tag;

    public function __construct(array $dados) {
        $this->id           = (int) ($dados['id']        ?? 0);
        $this->titulo       =        $dados['titulo']       ?? '';
        $this->descricao    =        $dados['descricao']       ?? '';
        $this->situacao     =        $dados['situacao']      ??  '';
        $this->nota         = (int) ($dados['nota'] ?? 0);
        $this->capa         =        $dados['capa']        ?? '';
        $this->IdAutor      = (int) ($dados['IdAutor']        ?? 0);
        $this->IdCategoria  = (int) ($dados['IdCategoria']        ?? 0);
        $this->Idusuario    = (int) ($dados['Idusuario']        ?? 0);
        $this->tag          =        $dados['tag']       ?? '';
    }

    public function getId():          int    { return $this->id; }
    public function getTitulo():      string { return $this->titulo; }
    public function getDescricao():   string { return $this->descricao; }
    public function getSituacao():    string { return $this->situacao; }
    public function getNota():        int    { return $this->nota; }
    public function getCapa():        string { return $this->capa; }
    public function getIdAutor():     int    { return $this->IdAutor; }
    public function getIdCategoria(): int    { return $this->IdCategoria; }
    public function getIdusuario():   int    { return $this->Idusuario; }
    public function getTag():         int    { return $this->tag; }

    public static function novo(string $titulo, string $descricao,string $situacao,int $nota,string $capa,int $IdAutor,int $IdCategoria,int $Idusuario, int $tag): Livro {
        if ($Idusuario <= 0) {
        throw new InvalidArgumentException('Usuário inválido.');
    }

    $livro = new Livro(['Idusuario' => $Idusuario]);
    $livro->alterarDados($titulo, $descricao, $situacao, $nota, $capa, $IdAutor, $IdCategoria, $tag);

    return $livro;
    }
    
    public function alterarDados(
    string $titulo, string $descricao, string $situacao, int $nota, string $capa, int $IdAutor, int $IdCategoria, int $tag): void {

    $titulo = trim($titulo);
    $situacao = trim($situacao);

    if ($titulo === '' || $situacao === '') {
        throw new InvalidArgumentException('Título e descrição são obrigatórios.');
    }

    if ($nota < 0 || $nota > 5) {
        throw new InvalidArgumentException('A nota deve ser entre 0 e 5.');
    }

    $this->titulo = $titulo;
    $this->descricao = $descricao;
    $this->situacao = $situacao;
    $this->nota = $nota;
    $this->capa = $capa;
    $this->IdAutor = $IdAutor;
    $this->IdCategoria = $IdCategoria;
    $this->tag = $tag;
    }


    public function registrarIdGerado(int $id): void {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID inválido.');
        }

        $this->id = $id;
    }
}
