<?php
$host = "localhost"; // Host do MySQL
$user = "trow"; // Usuário do MySQL
$senha = "123"; // Senha do MySQL
$database = "trabalho"; // Banco de dados do MySQL*/

$mysqli = new mysqli($host,$user,$senha,$database);
if ($mysqli->connect_errno) {
    printf("Falha na Conexão: %s\n", $mysqli->connect_error);
    exit();
}
if(!$mysqli->set_charset("utf8")){
	printf("Falha ao setar a conexao como UTF8.");
	exit();
}

$mysqli->query("CREATE TABLE IF NOT EXISTS `agenda` (
	`id` int not null AUTO_INCREMENT,
	`evento` varchar(80) not null,
	`descricao` varchar(255) not null,
	`data_timestamp` int not null,
	`criado_em` int not null,
	PRIMARY KEY(id));");