<?php


const URL = "http://localhost/api/pergunta.php";

$curl = curl_multi_init();

$handlers = [ ];
for ($i = 0; $i < 1000; $i++) {
    $ch = curl_init(URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    $handlers[] = $ch;
    curl_multi_add_handle($curl, $ch);
}

$start = time();
do {
    $status = curl_multi_exec($curl, $active);

    $end = time();
    $total = $end  - $start;
    echo "Rodando... ($total)\r";
} while ($active);

$total = $end -$start;
echo "Tempo total: ($total)s".PHP_EOL;

$frases = [ ];
foreach ($handlers as $handler) {
    $response = (curl_multi_getcontent($handler));
    $response = json_decode($response,true);
    curl_close($handler);

    if (!isset($frases[ $response['pergunta'] ])){
        $frases[ $response['pergunta'] ] = 0;
    }

    $frases[ $response['pergunta'] ] += 1;
}

curl_multi_close( $curl );


print_r($frases);