<?php

namespace App\Interfaces;

interface RolesInterface 
{
    public function index($array); 
    public function create(); 
    public function store($array); 
    public function edit($id); 
    public function update($array , $role); 
    public function delete($id);  
   
}