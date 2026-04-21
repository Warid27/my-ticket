<?php
require_once 'app/core/BaseController.php';

class HomeController extends BaseController
{
    public function index(): void{
        require 'app/views/home.php';
    }
}