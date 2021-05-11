<?php
include '../php/controller/Base.php';
include '../database/DbConn.php';
include '../database/SendTypePdo.php';
include '../database/SendLogPdo.php';
include '../php/DataStorage.php';
include '../php/exceptions/CustomException.php';

$base = new Base();

//different behavior for input's method
switch($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': $base->read($_GET['type'], $_GET['text']); break;
    case 'POST': echo('not yet implemented'); break;
    default: echo ('invalid method');
}