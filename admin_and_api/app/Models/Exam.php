<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';
    use HasFactory;

    public function subjects()
    {
        return $this->belongsToMany(Subject::class,Exam_Subject::class, 'exam_id', 'subject_id');
    }
}
