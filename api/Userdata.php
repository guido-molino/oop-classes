<?php

header("Content-Type: application/json; charset=UTF-8");

include '../php/controller/UserdataController.php';

//istanzio lo user con parametro request
$user = new UserdataController($_REQUEST);
//processo lo user
$user->process();
