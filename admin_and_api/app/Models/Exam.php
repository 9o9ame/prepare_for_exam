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
        return $this->belongsToMany(Subject::class, 'exam_subjects', 'exam_id', 'subject_id');
    }
    public function boards()
    {
        return $this->belongsToMany(Board::class,Exam_Subject_Board::class, 'subject_id', 'board_id');;
    }
}
