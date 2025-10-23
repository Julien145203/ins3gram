<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
    protected $breadcrumb = [['text' => 'Tableau de Bord', 'url' => '']];
    public function dashboard() {
        return $this->view('admin/dashboard');
    }
}
