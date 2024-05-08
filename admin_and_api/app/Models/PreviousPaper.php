<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousPaper extends Model
{
    protected $table = 'previous_year_papers';
    use HasFactory;
	
	  public function exam()
    {
        return $this->belongsTo(Exam::class,'exam_id');
    }
	  public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_name');
    }
	  public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }
}
