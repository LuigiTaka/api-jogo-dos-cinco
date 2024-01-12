<?php
require_once __DIR__."/../src/Database.php";

/**
 * Arquivo de inicialização.
 * Cria as tabelas do banco de dados utilizando os arquivos presentes no diretório sql/
 *
 */


const SQL_DIR = __DIR__."/../sql";
$sql = file_get_contents(SQL_DIR."/000_init.sql");
$connection = Database::get();

$resultado = $connection->exec( $sql );

echo "Banco de dados criado com sucesso!".PHP_EOL;

include __DIR__."/insert.php";