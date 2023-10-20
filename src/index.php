<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "config.php";

$method = $_GET['method'];
$postData = empty($_POST) ? json_decode(file_get_contents('php://input', 'r'), 1) : $_POST;
switch ($method) {
    case 'List':
        $finance = new Finance(new FinanceSearch($postData));
        echo json_encode($finance->getList());
        break;
    case 'Add':
        break;
    case 'Update':
        break;
    case 'Delete':
        break;
    case 'GetID':
        $finance = new Finance(new FinanceSearch($postData));
        echo json_encode([
            'ID' => $finance->getID()
        ]);
        break;
}