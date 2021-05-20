<?php

header("Content-Type: application/json; charset=UTF-8");

include '../php/controller/UserController.php';

//istanzio lo user con parametro request
$user = new UserController($_REQUEST);
//processo lo user
$user->process();
