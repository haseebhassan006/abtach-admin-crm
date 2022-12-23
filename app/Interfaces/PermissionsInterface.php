<?php

namespace App\Interfaces;

interface PermissionsInterface {
    public function index($array);
    public function create();
    public function store($name , $perms , $roles);
}

?>