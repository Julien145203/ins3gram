<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Opinion extends BaseController
{
    public function index()
    {
        helper('form');
        return $this->view('admin/opinion');
    }
}
