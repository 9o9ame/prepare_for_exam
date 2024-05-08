<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question_exam extends Model
{
    use HasFactory;
    protected $fillable = ['question_id', 'exam_id'];
}
