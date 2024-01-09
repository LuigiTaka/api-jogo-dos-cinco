<?php


require_once __DIR__ . "/../src/Database.php";

const JSON_PERGUNTAS_PATH = __DIR__ . "/perguntas.json";
$connection = Database::get();

//considerando que as perguntas e respostas estarão em um arquivo JSON
if (!file_exists(JSON_PERGUNTAS_PATH)) {
    echo "Arquivo " . JSON_PERGUNTAS_PATH . " não existe" . PHP_EOL;
    exit();
}

$perguntas = json_decode(file_get_contents(JSON_PERGUNTAS_PATH), true);


$stmt = $connection->query("SELECT pergunta,id FROM jogo_dos_cinco.perguntas");
$stmt->execute();
$perguntasMap = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

$connection->beginTransaction();
foreach ($perguntas as $index => $row) {

    $pergunta = htmlspecialchars(
        trim(
            mb_convert_case($row['pergunta'],MB_CASE_LOWER)
        )
    );
    $respostas = $row['respostas'];
    $respostas = array_map( function ($p) {
        return  htmlspecialchars(
            trim(
                mb_convert_case($p,MB_CASE_LOWER)
            )
        );
    }, $respostas );

    try{

        if (!isset($perguntasMap[$pergunta])) {
            $stmt = $connection->prepare("INSERT INTO jogo_dos_cinco.perguntas(pergunta) VALUES(?)");
            $stmt->execute([$pergunta]);
            $id = $connection->lastInsertId("perguntas" );
            echo "Inserindo pergunta #$id".PHP_EOL;
            $perguntasMap[$pergunta] = $id;
        }

        $idPergunta = $perguntasMap[$pergunta];

        $values2insert = [];
        $params = [  ];
        foreach ($respostas as $resposta) {
            $params[] = $resposta;
            $params[] = $idPergunta;
            $values2insert[] = "(?,?)";
        }
        $values2insert = implode(",",$values2insert);
        $insertRespostaQuery = <<<SQL
INSERT IGNORE INTO jogo_dos_cinco.respostas(resposta,id_pergunta) VALUES $values2insert
SQL;
        $stmt = $connection->prepare($insertRespostaQuery);
        $result = $stmt->execute( $params );

    }catch (\Throwable $e){
        echo "Um erro foi detectado: ".$e->getMessage().PHP_EOL;
        $connection->rollBack();
    }

}

$connection->commit();