<?php

require_once 'models/Aluno.php';

class AlunoDaoMysql {

    private $pdo;

    public function __construct(PDO $engine){
        $this->pdo = $engine;
    }
  
    public function alunoGenerator ($array){
        $u = new Aluno ();
        $u->setId($array['id'])?? 0;
        $u->setNome($array['nome'])?? 0;
        $u->setTurma($array['turma'])?? 0;

        return $u;

    }
    public function findAll()
    {
        $array = [];

        $sql = $this->pdo->query("SELECT * FROM aluno");
        if ($sql->rowCount()>0){
            $dados = $sql->fetchAll();

            foreach($dados as $item){
           
                $aluno = $this->alunoGenerator($item);
                $array[] = $aluno;
            }
        }
        return $array;
    }
    public function findById($id)
    {
        $sql = $this->pdo->prepare("SELECT * FROM aluno WHERE id=:id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount()>0){
            $info = $sql->fetch(PDO::FETCH_ASSOC);
            $user=$this->alunoGenerator($info);
            return $user;
        }else{
            return false;
        }
    }
    
    public function addAluno(Aluno $m)
    {
        $sql = $this->pdo->prepare("INSERT INTO aluno (nome, turma) VALUES (:nome, :turma)");
        $sql->bindValue(':nome',$m->getNome());
        $sql->bindValue(':turma', $m->getTurma());
        $sql->execute();

        $m->setId($this->pdo->lastInsertId());
        return true;
    }

    public function deleteAluno($id)
    {
        $sql=$this->pdo->prepare("DELETE FROM aluno WHERE id=:id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

    public function updateAluno (Aluno $u){
        $sql = $this->pdo->prepare("UPDATE aluno SET nome=:nome, turma=:turma WHERE id=:id");
        $sql->bindValue(':nome',$u->getNome() );
        $sql->bindValue(':turma', $u->getTurma());
        $sql->bindValue(':id', $u->getId());
        $sql->execute();

        return true;
    }
    public function deleteAluno ($id){
        $sql=$this->pdo->prepare("DELETE FROM aluno WHERE id=:id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

}
