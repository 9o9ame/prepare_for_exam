<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;
class StudentProfile extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'student_profiles';
    protected $hidden = ['password', 'remember_token'];
   // use HasFactory;
}
