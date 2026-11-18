<?php

require_once __DIR__ . "/../models/Usuario.php";

class UsuarioController
{
    private $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function listar()
    {
        exigirAdmin();

        return $this->model->listar();
    }
}
