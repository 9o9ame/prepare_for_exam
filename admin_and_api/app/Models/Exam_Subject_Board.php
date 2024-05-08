<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_Subject_Board extends Model
{
    protected $table = 'exam_subject_boards';
    use HasFactory;

    public function exams()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function boards()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }
}
