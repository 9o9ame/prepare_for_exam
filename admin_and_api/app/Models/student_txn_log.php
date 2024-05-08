<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student_txn_log extends Model
{
    use HasFactory;
    protected $table = 'student_txn_log';

    public function plan()
    {
        return $this->belongsTo(Subscription::class,'subscription_id','id');
    }
}
