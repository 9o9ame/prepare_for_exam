<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board_Exam extends Model
{
    protected $table = 'board_exams';
    use HasFactory;

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
