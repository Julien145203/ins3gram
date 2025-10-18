<?php
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth:administrateur'], function ($routes) {
    //Routes vers le tableau de bord
    $routes->get('dashboard', 'Admin::dashboard');

    $routes->group('user', function ($routes) {
        $routes->get('/', 'User::index');
        $routes->get('(:num)', 'User::edit/$1');
        $routes->get('new', 'User::create');
        $routes->post('update', 'User::update');
        $routes->post('insert', 'User::insert');
        $routes->post('switch-active','User::switchActive');
        $routes->get('search', 'User::search');
    });

    $routes->group('user-permission', function ($routes) {
        $routes->get('/', 'UserPermission::index');
        $routes->post('update', 'UserPermission::update');
        $routes->post('insert', 'UserPermission::insert');
        $routes->post('delete', 'UserPermission::delete');
    });

    $routes->group('recipe', function ($routes) {
        $routes->get('/', 'Recipe::index');
        $routes->get('(:num)', 'Recipe::edit/$1');
        $routes->get('new', 'Recipe::create');
        $routes->post('insert', 'Recipe::insert');
        $routes->post('update', 'Recipe::update');
        $routes->post('switch-active', 'Recipe::switchActive');
    });

    $routes->group('brand', function ($routes) {
        $routes->get('/', 'Brand::index');
        $routes->post('update', 'Brand::update');
        $routes->post('insert', 'Brand::insert');
        $routes->post('delete', 'Brand::delete');
        $routes->get('search', 'Brand::search');
    });

    $routes->group('ingredient', function ($routes) {
        $routes->get('search', 'Ingredient::search');
        $routes->get('/', 'Ingredient::index');
        $routes->get('edit/(:num)', 'Ingredient::edit/$1');   // Affichage formulaire création
         $routes->get('new', 'Ingredient::new'); // Affichage formulaire édition
         $routes->post('insert', 'Ingredient::insert');
        $routes->post('update', 'Ingredient::update');        // Soumission formulaire édition
        $routes->post('delete', 'Ingredient::delete');        // Suppression via POST
    });

    $routes->group('unit', function ($routes) {
        $routes->get('/', 'Unit::index');
        $routes->get('search', 'Unit::search');
        $routes->post('update', 'Unit::update');
        $routes->post('insert', 'Unit::insert');
        $routes->post('delete', 'Unit::delete');
    });

    $routes->group('tag', function ($routes) {
        $routes->get('/', 'Tag::index');
        $routes->post('insert', 'Tag::insert');
        $routes->post('update', 'Tag::update');
        $routes->post('delete', 'Tag::delete');
    });

    $routes->group('category-ingredient', function ($routes) {
        $routes->get('/', 'CategIng::index');
        $routes->post('insert', 'CategIng::insert');
        $routes->post('update', 'CategIng::update');
        $routes->post('delete', 'CategIng::delete');
        $routes->get('getValidParents', 'CategIng::getValidParents');
        $routes->get('datatable', 'CategIng::datatable'); // pour DataTable
        $routes->get('search', 'Ingredient::search');
    });


});