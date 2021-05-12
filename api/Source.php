<?php
include '../database/DbConn.php';
include '../php/controller/SourceController.php';

$base = new SourceController();

//different behavior for input's method
switch($_SERVER['REQUEST_METHOD']) {
    
    case 'GET': $base->read($_GET['type'], $_GET['text']); break;
    case 'POST': echo('not yet implemented'); break;
    default: echo ('invalid method'); break;
}