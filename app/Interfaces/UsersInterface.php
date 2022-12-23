<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UsersInterface
{
    public function index($array);
    public function create();
    public function store($array);
    public function edit($id);
    public function show($id);
    public function update($array, $id);
    public function delete($id);
    public function getRoles($id);
    public function directPermission($id);
    public function storeDirectPermission($array);
    public function deleteDirectPermission($array);
}
