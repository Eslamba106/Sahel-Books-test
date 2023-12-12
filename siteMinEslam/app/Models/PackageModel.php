<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageModel extends Model
{
    use HasFactory;
    protected $table = 'package';
    public $timestamps = false;
}
