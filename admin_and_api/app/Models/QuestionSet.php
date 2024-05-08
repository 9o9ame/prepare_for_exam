<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    protected $table = 'question_sets';
    use HasFactory;

	public function reads()
	{
		 return $this->hasOne(student_question_read::class,'question_id','id');
	}

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'question_exams', 'question_id', 'exam_id');
    }
    public function boards()
    {
        return $this->belongsToMany(Board::class, 'question_boards', 'question_id', 'board_id');
    }

	public function notes()
	{
		return $this->hasOne(student_question_note::class,'question_id','id');
	}
}
