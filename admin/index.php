<?php
session_start();
// session_destroy();
    require_once '../inc/Database.php'; // подключение к БД

    include_once("modelAdmin/modelAdmin.php");
    include_once("modelAdmin/modelAdminNews.php");
    include_once("modelAdmin/modelAdminCategory.php");

    include_once("controllerAdmin/controllerAdmin.php");
    include_once("controllerAdmin/controllerAdminNews.php");

    include('routeAdmin/routingAdmin.php');//!!!

    echo $response;