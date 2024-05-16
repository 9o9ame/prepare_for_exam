<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::POST('create-student-profile', [StudentProfileController::class, 'create_student_profile'])->name('student-register');
Route::POST('login-student', [StudentProfileController::class, 'login_student']);
Route::POST('otp-verification', [StudentProfileController::class, 'otp_verification']);
// Route::GET('fetch-exams', [StudentProfileController::class, 'fetch_exams']);
Route::GET('fetch-subjects', [StudentProfileController::class, 'fetch_subjects']);
Route::GET('fetch-boards', [StudentProfileController::class, 'fetch_boards']);
Route::GET('fetch-subject-topic', [StudentProfileController::class, 'fetch_subject']);
Route::GET('fetch-country', [StudentProfileController::class,'fetch_country']);
Route::GET('fetch-question-details', [StudentProfileController::class, 'question_details']);
// Route::GET('fetch-question', [StudentProfileController::class, 'fetch_question']);
Route::POST('fetch-subscription-panel', [StudentProfileController::class, 'fetch_subscription_panel']);


Route::middleware('auth:api')->group(function () {
    Route::POST('update_question_notes', [StudentApiController::class, 'update_question_notes']);
	Route::POST('get_student_profile', [StudentProfileController::class, 'get_student_profile']);
	Route::POST('create_order_request',[StudentApiController::class,'create_order_request']);
	Route::POST('verify_order',[StudentApiController::class,'verify_order']);

    Route::POST('fetch_dashboard',[StudentApiController::class,'fetch_dashboard']);

	 Route::middleware('subscription')->group(function() {
    Route::GET('fetch-exams', [StudentProfileController::class, 'fetch_exams']);

	Route::POST('generate_pdf',[StudentApiController::class,'generate_pdf']);

    Route::POST('update_student_profile_api', [StudentProfileController::class, 'update_student_profile_api']);

    Route::POSt('fetch_exam', [StudentApiController::class, 'fetch_exam']);
    Route::POST('fetch_exam_data', [StudentApiController::class,'fetch_exam_data']);

    Route::POST('activate_board', [StudentApiController::class,'active_board']);

    Route::POST('fetch_subscription_data',[StudentApiController::class,'fetch_subscription_data']);

    Route::POST('fetch_user_exam_data',[StudentApiController::class,'fetch_user_exam_data']);

    Route::POST('fetch-question-details', [StudentProfileController::class, 'question_details']);
    Route::POST('fetch-question', [StudentApiController::class, 'fetch_question']);

    Route::POST('change_password', [StudentApiController::class, 'change_password']);

    Route::POST('update_question_notes', [StudentApiController::class, 'update_question_notes']);

	Route::POST('fetch_topics', [StudentApiController::class, 'fetch_topics']);

	Route::POST('fetch_questions', [StudentApiController::class, 'fetch_questions']);

	Route::POST('update_question_status', [StudentApiController::class, 'update_question_status']);

    //for logout
    // Route::post('logout_user', [AuthController::class,'logout']);
});
});

//Teacher Section Routes
Route::POST('create-teacher-profile', [TeacherProfileController::class, 'create_teacher_profile']);
Route::POST('login-teacher', [TeacherProfileController::class, 'login_teacher']);
Route::POST('otp-verification-teacher', [TeacherProfileController::class, 'otp_verification_teacher']);


//condition  for protect the Teacher route
Route::middleware('auth:teacher-api')->group(function () {
    // Route::post('logout_vendor', [AuthController::class,'logout']);
});
