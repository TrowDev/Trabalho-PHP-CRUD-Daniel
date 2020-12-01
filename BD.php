<?php

class DB{
	
	function __construct(){
		// nothing
    }

    public function listarAgenda($mysqli) {
        $agora = time();
        $sql = $mysqli->prepare("SELECT * FROM agenda WHERE data_timestamp > ? ORDER BY data_timestamp ASC");
        $sql->bind_param("i", $agora);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($id, $evento, $descricao, $tsEvento, $criadoEm);
        if($sql->num_rows>0){
            $ret = array();
            while ($s = $sql->fetch()) {
                $dtEvento = date('d/m/Y', $tsEvento);
                $dtCriado = date('d/m/Y H:i:s', $criadoEm);
                $evento = array('id' => $id, 
                    'evento' => $evento, 
                    'descricao' => $descricao, 
                    'dataEvento' => $dtEvento, 
                    'criadoEm' => $dtCriado);
                array_push($ret, $evento);
            }
            return $ret;
        }
        return null;
    }

    public function findEvento($mysqli, $eventoID) {
        $sql = $mysqli->prepare("SELECT * FROM agenda WHERE id=? ORDER BY data_timestamp ASC");
        $sql->bind_param("i", $eventoID);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($id, $evento, $descricao, $tsEvento, $criadoEm);
        if($sql->num_rows>0){
            $ret = array();
            while ($s = $sql->fetch()) {
                $dtEvento = date('d/m/Y', $tsEvento);
                $dtCriado = date('d/m/Y H:i:s', $criadoEm);
                $evento = array('id' => $id, 
                    'evento' => $evento, 
                    'descricao' => $descricao, 
                    'dataEvento' => $dtEvento, 
                    'criadoEm' => $dtCriado);
                array_push($ret, $evento);
            }
            return $ret;
        }
        return null;
    }

    public function addEvento($mysqli, $eventoDados){
        $evento             = isset($eventoDados['evento']) ? $eventoDados['evento']        : '';
        $descricao          = isset($eventoDados['descricao']) ? $eventoDados['descricao']  : '';
        $data               = isset($eventoDados['data']) ? $eventoDados['data']            : '';
        $time               = time();
        $sql = $mysqli->prepare("INSERT INTO agenda (`evento`, `descricao`, `data_timestamp`, `criado_em`) VALUES (?,?,?,?);");
        $sql->bind_param("ssii", $evento, $descricao, $data, $time);
        $sql->execute();
        return $sql->affected_rows > 0;
    }

    public function updateEvento($mysqli, $eventoDados){
        $eventoID               = isset($eventoDados['eventoID']) ? $eventoDados['eventoID']            : '';
        $evento                 = isset($eventoDados['evento']) ? $eventoDados['evento']                : '';
        $descricao              = isset($eventoDados['descricao']) ? $eventoDados['descricao']          : '';
        $data                   = isset($eventoDados['data']) ? $eventoDados['data']                    : '';
        $sql = $mysqli->prepare("UPDATE agenda SET evento=?,descricao=?,data_timestamp=? WHERE id=?");
        $sql->bind_param("ssii", $evento, $descricao, $data, $eventoID);
        $sql->execute();
        return $sql->affected_rows > 0;
    }

    public function delEvento($mysqli, $eventoID) {
        $sql = $mysqli->prepare("DELETE FROM agenda WHERE id=?");
        $sql->bind_param("i", $eventoID);
        $sql->execute();
        return $sql->affected_rows>0;
    }

}

?>