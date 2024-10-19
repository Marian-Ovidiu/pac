<?php
$router = \Core\Router::getInstance();

$router->request('/test', function () {
    echo 'Ecco la risposta';
});