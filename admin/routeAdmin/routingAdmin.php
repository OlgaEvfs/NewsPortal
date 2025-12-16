<?php
$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$num = substr_count($host,'/');
$path = explode('/', $host)[$num];

if ($path == '' OR $path == 'index.php' ) {
    // Главная страница -
    $response = controllerAdmin::formLoginSite();
}
// ---------- ВХОД ------------
elseif ($path == 'login') {
    // форма входа
    $response = controllerAdmin::loginAction();
}
elseif ($path == 'logout') {
    // Выход
    $response = controllerAdmin::logoutAction();
}
//---------------------listNews
elseif ($path == 'newsAdmin') {
    $response = controllerAdminNews::NewsList();
}
//-------------add news
elseif ($path == 'newsAdd') {
    $response = controllerAdminNews::newsAddForm();
}
elseif ($path == 'newsAddResult') {
    $response = controllerAdminNews::newsAddResult();
}
else {
    // Страница не существует
    $response = controllerAdmin::error404();
}