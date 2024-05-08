<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingQuery extends Model
{
    use HasFactory;
    protected $table = "queries";
}
