<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return $this->view('welcome_message',[],false);
    }
}
