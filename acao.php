<?php
include('./config.php');
include('./BD.php');
$db = new DB();

$acao = isset($_GET['acao']) ? $_GET['acao'] : $_POST['acao'];

if($acao == "listar") {
    echo json_encode(array('retorno' => $db->listarAgenda($mysqli)));
    return;
} else if($acao == "buscar") {
    $eventoID = isset($_GET['eventoID']) ? $_GET['eventoID'] : FALSE;
    if(!isset($eventoID) || !$eventoID){
        echo json_encode(array('retorno'=>"ID do evento agendado não informado.", 'erro' => true));
        return;
    }
    if($eventoID < 0){
        echo json_encode(array('retorno'=>"ID do Evento é inválido!", 'erro' => true));
        return;
    }
    echo json_encode(array('retorno' => $db->findEvento($mysqli, $eventoID)));
    return;
} else if($acao == "inserir"){
    if(!isset($_POST) || count($_POST) == 0){
        $_POST = json_decode(file_get_contents('php://input'), true);
    }
    extract($_POST);
    if(!isset($evento) || empty($evento)){
        echo json_encode(array('retorno'=>"Informe o nome do evento.", 'erro' => true));
        return;
    }
    if(!isset($descricao) || empty($descricao)){
        echo json_encode(array('retorno'=>"Informe a descrição do evento.", 'erro' => true));
        return;
    }
    if(!isset($data) || empty($data)){
        echo json_encode(array('retorno'=>"Informe a data do evento.", 'erro' => true));
        return;
    }
    $dtEvento = DateTime::createFromFormat('d/m/Y', $data);
    $tsEvento = $dtEvento->getTimestamp();
    $dadosEvento = array(
        'evento'        => $evento,
        'descricao'     => $descricao,
        'data'          => $tsEvento
    );
    if($db->addEvento($mysqli, $dadosEvento)){
        echo json_encode(array('retorno'=>"Evento inserido com sucesso!"));
    } else {
        echo json_encode(array('retorno'=>"Não foi possível salvar seu evento.", 'erro' => true));
    }
    return;
} else if($acao == "atualizar"){
    if(!isset($_POST) || count($_POST) == 0){
        $_POST = json_decode(file_get_contents('php://input'), true);
    }
    extract($_POST);
    if(!isset($eventoID) || $eventoID < 0){
        echo json_encode(array('retorno'=>"ID do evento agendado não informado.", 'erro' => true));
        return;
    }
    if(!isset($evento) || empty($evento)){
        echo json_encode(array('retorno'=>"Informe o nome do evento.", 'erro' => true));
        return;
    }
    if(!isset($descricao) || empty($descricao)){
        echo json_encode(array('retorno'=>"Informe a descrição do evento.", 'erro' => true));
        return;
    }
    if(!isset($data) || empty($data)){
        echo json_encode(array('retorno'=>"Informe a data do evento.", 'erro' => true));
        return;
    }
    $dtEvento = DateTime::createFromFormat('d/m/Y', $data);
    $tsEvento = $dtEvento->getTimestamp();
    $dadosEvento = array(
        'evento'        => $evento,
        'descricao'     => $descricao,
        'data'          => $tsEvento,
        'eventoID'      => $eventoID
    );
    if($db->updateEvento($mysqli, $dadosEvento)){
        echo json_encode(array('retorno'=>"Evento atualizado com sucesso!"));
    } else {
        echo json_encode(array('retorno'=>"Não foi possível salvar sua alteração no evento.", 'erro' => true));
    }
    return;
} else if($acao == "remover"){
    $eventoID = isset($_GET['eventoID']) ? $_GET['eventoID'] : FALSE;
    if(!isset($eventoID) || !$eventoID){
        echo json_encode(array('retorno'=>"ID do evento agendado não informado.", 'erro' => true));
        return;
    }
    if($eventoID < 0){
        echo json_encode(array('retorno'=>"ID do Evento é inválido!", 'erro' => true));
        return;
    }
    if($db->delEvento($mysqli, $eventoID)){
        echo json_encode(array('retorno'=>"Evento removido com sucesso!"));
    } else {
        echo json_encode(array('retorno'=>"Erro ao remover seu evento.", 'erro' => true));
        return;
    }
}
?>