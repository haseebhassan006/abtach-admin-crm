<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DummyPermissions extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
}
