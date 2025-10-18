<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Option extends BaseController
{
    public function index()
    {
        helper('form');
        return $this->view('admin/option');
    }
}
