<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KernelLog extends Model
{
    use HasFactory;
    protected $table = 'kernelLog';
    protected $primaryKey = 'idLog';
}
