<?php
$routes->get('/', 'Home::index');
$routes->get('/forbidden','Site::forbidden');

$routes->get('/test','Site::test');

$routes->get('/sign-in', 'Auth::signIn');
$routes->get('/register', 'User::register');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');

$routes->group('recette', function ($routes) {
    $routes->get('/', 'Recipe::index');
    $routes->get('(:any)', 'Recipe::show/$1');
});

$routes->group('user', function ($routes) {
    $routes->post('insert', 'User::insert');
    $routes->post('update', 'User::update');
});

$routes->group('messagerie', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Chat::index');
    $routes->get('conversation', 'Chat::conversation');
    $routes->get('new-messages', 'Chat::newMessages');
    $routes->get('historique', 'Chat::historique');
    $routes->post('send', 'Chat::send');
});

$routes->group('contactez-nous', function ($routes) {
    $routes->get('/', 'Contact::index');
    $routes->post('send', 'Contact::send');
});

// Routes favoris
$routes->group('favoris', function ($routes) {
    $routes->get('/', 'UserFavorites::index');
    $routes->post('toggle', 'UserFavorites::toggle');
    $routes->get('count', 'UserFavorites::count');
});

//dataTable
$routes->post('/datatable/searchdatatable', 'DataTable::searchdatatable');