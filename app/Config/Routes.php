
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Routes de l'administration
require APPPATH . 'Config/Routes/Admin.php';

//Routes du site (front)
require APPPATH . 'Config/Routes/Site.php';

//Routes API
require APPPATH . 'Config/Routes/Api.php';