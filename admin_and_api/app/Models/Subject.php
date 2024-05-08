<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    use HasFactory;

    public function boards()
    {
        return $this->belongsToMany(Board::class,Exam_Subject_Board::class, 'subject_id', 'board_id');;
    }

}
