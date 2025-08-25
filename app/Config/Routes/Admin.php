<?php
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth:administrateur'], function ($routes) {
    //Routes vers le tableau de bord
    $routes->get('dashboard', 'Admin::dashboard');
});