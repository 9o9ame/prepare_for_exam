<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student_question_read extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'question_id', 'type'];
}
