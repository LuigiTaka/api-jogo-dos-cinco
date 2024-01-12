<?php

class PerguntaModel
{

    /**
     * @param PDO $connection
     */
    public function __construct(protected \PDO $connection)
    {
    }


    public function insertPergunta(string $pergunta)
    {
        $insertQuery = <<<QUERY
INSERT IGNORE INTO jogo_dos_cinco.perguntas(pergunta) VALUES (?)
QUERY;

        $this->connection->beginTransaction();
        $stmt = $this->connection->prepare($insertQuery);
        $stmt->execute([$pergunta]);
        $id = $this->connection->lastInsertId("perguntas");
        $this->connection->commit();

        if (empty($id)){
            $id = $this->getPerguntaId( $pergunta );
        }

        return $id;
    }

    public function insertRespostas(string $idPergunta, array $respostas)
    {
        $values2insert = [];
        $params = [];
        foreach ($respostas as $resposta) {
            $params[] = $resposta;
            $params[] = $idPergunta;
            $values2insert[] = "(?,?)";
        }
        $values2insert = implode(",", $values2insert);
        $insertRespostaQuery = <<<SQL
INSERT IGNORE INTO jogo_dos_cinco.respostas(resposta,id_pergunta) VALUES $values2insert
SQL;
        $stmt = $this->connection->prepare($insertRespostaQuery);
        $result = $stmt->execute($params);
        return $result;
    }


    public function getPerguntaId(string $pergunta)
    {
        $query = <<<SQL
SELECT id FROM jogo_dos_cinco.perguntas WHERE LOWER(pergunta) = LOWER(?) LIMIT 1
SQL;
        $stmt = $this->connection->prepare($query);
        $stmt->execute([ $pergunta ]);
        return $stmt->fetch(\PDO::FETCH_COLUMN);
    }

}