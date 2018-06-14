<?php

    include_once('cors_config.php');
    include_once('debug_config.php');
    include_once('db_config.php');

    $messeges = array();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = $_POST['d'];

        if(is_string($data) && $data != '') {   
            if ($db) { 
                $SQL = $db->prepare("INSERT INTO `consentimientos` (`datos`) VALUES (?)");
                $SQL->bind_param('s', $data);
                $result = $SQL->execute();
                if (!$result) {
                    $messeges['error'] = 'No se pudo insertar los datos.';
                } else {
                    $messeges['success'] = true;
                }
            } else {
                $messeges['error'] = 'No se pudo conectar con la base de datos.'; 
            }
        } else {
            $messeges['error'] = 'Los datos deben contener un JSON en el parametro "d".';
        } 
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // nivel_admin($email, $pass, $db);
        // echo $db;
        $SQL = $db->prepare('SELECT * FROM `consentimientos`');
        // echo $SQL;
        $SQL->execute();
        $result = $SQL->get_result();
        $SQL->free_result();
        $SQL->close();
        $lista_usuarios = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($messeges, json_decode($row['datos']));
        }
    }

    header('Content-Type: application/json; charset=utf-8;');
    echo json_encode($messeges, JSON_UNESCAPED_UNICODE); 
?>