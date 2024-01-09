<?php

require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/Utils.php";
require_once __DIR__ . "/../src/PerguntaModel.php";

$connection = Database::get();

$method = $_SERVER['REQUEST_METHOD']??"GET";


$perguntaModel = new PerguntaModel($connection);


if ($method === 'GET') {

    $perguntaStmt = $connection->query("SELECT * FROM jogo_dos_cinco.perguntas ORDER BY RAND() LIMIT 1");
    $perguntaStmt->execute();
    $pergunta = $perguntaStmt->fetch(\PDO::FETCH_ASSOC);

    $respostasStmt = $connection->prepare("SELECT * 
FROM jogo_dos_cinco.respostas 
WHERE id_pergunta = ? 
ORDER BY RAND() 
LIMIT 10");
    $respostasStmt->execute([$pergunta['id']]);
    $respostas = $respostasStmt->fetchAll(\PDO::FETCH_ASSOC);

    $headers = [
        "Content-Type: application/json",
        "Cache-Control: public, max-age=20"
    ];

    echo Utils::response([
        "pergunta" => $pergunta['pergunta'],
        "resposta" => $respostas
    ], $headers);
    exit();
} else if ($method === 'POST') {


    $bearerToken = Utils::getBearerToken()??"";
    $isValid = Utils::isValidToken($bearerToken);
    if (!$isValid) {
        echo Utils::response(["token" => $bearerToken], [], 404);
        exit();
    }

    $idPergunta = $_POST['id_pergunta'] ?? false;
    $pergunta = $_POST['pergunta'] ?? false;
    $respostas = $_POST['respostas'] ?? [];


    $pergunta = Utils::sanitizeString($pergunta);
    Utils::checkPergunta($pergunta);

    $respostas = array_map(function ($resposta) {
        return Utils::sanitizeString($resposta);
    }, $respostas);


    if (empty($pergunta)) {
        echo Utils::response([
            "message" => "Nenhuma pergunta informada!",
        ], ["Content-Type: application/json"], 427);
        exit();
    }

    if (empty($respostas)) {
        echo Utils::response([
            "message" => "Nenhuma resposta informada!",
        ], ["Content-Type: application/json"], 427);
        exit();
    } elseif (count($respostas) < 2) {

        echo Utils::response([
            "message" => "Informe ao menos duas respostas!",
        ], ["Content-Type: application/json"], 427);
        exit();
    }

    $id = $perguntaModel->insertPergunta($pergunta);
    $perguntaModel->insertRespostas($id, $respostas);

    echo Utils::response([
        "id_pergunta" => $id,
        "mensagem" => "Pergunta inserida!"
    ], [
        "Content-Type: application/json",
    ]);
    exit();

} else if ($method === 'PUT') {


    $bearerToken = Utils::getBearerToken();
    $isValid = Utils::isValidToken($bearerToken);
    if (!$isValid) {
        echo Utils::response(["token" => $bearerToken], [], 404);
        exit();
    }

    //@todo melhorar isso
    parse_str(file_get_contents("php://input"),$putData);


    $idPergunta = $putData['id_pergunta'] ?? false;
    $pergunta = $putData['pergunta'] ?? false;
    $respostas = $putData['respostas'] ?? [];

    if (empty($idPergunta)) {
        echo Utils::response([
            "message" => "Nenhum id de pergunta informado!",
        ], ["Content-Type: application/json"], 427);
        exit();
    }

    if (empty($respostas)) {
        echo Utils::response([
            "message" => "Nenhuma resposta informada!",
        ], ["Content-Type: application/json"], 427);
        exit();
    } elseif (count($respostas) < 2) {

        echo Utils::response([
            "message" => "Informe ao menos duas respostas!",
        ], ["Content-Type: application/json"], 427);
        exit();
    }


    $perguntaModel->insertRespostas( $idPergunta,$respostas );

    echo Utils::response([
        "id_pergunta" => $idPergunta,
        "mensagem" => "Respostas adicionadas!"
    ], [
        "Content-Type: application/json",
    ]);

}
