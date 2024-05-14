<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AllQueryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminEmailController;
use App\Http\Controllers\ClientlogoController;
use App\Http\Controllers\StudentApiController;
use App\Http\Controllers\QuestionSetController;
use App\Http\Controllers\TestimonialController;

use App\Http\Controllers\ManageStatusController;
use App\Http\Controllers\PendingQueryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PreviousPaperController;
use App\Http\Controllers\RevisionNotesController;
use App\Http\Controllers\CompletedQueryController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\EmailSubscriberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AdminController::class, 'index']);
Route::post('admin/auth', [AdminController::class, 'auth']);
Route::get('admin/logout', [AdminController::class, 'logout']);
Route::post('admin/get_status', [AdminController::class, 'get_status']);

Route::get('teacher_signup', [AdminController::class, 'signup_page']);
Route::post('register_teacher', [AdminController::class, 'register_teacher']);
Route::post('fetchcountrycode', [StudentProfileController::class, 'fetchcode']);

Route::get('payment_status', [AdminController::class, 'payment_status']);
// Route::get('admin/logout',function(){
//     return "Hello";
// });
Route::group(['middleware' => 'student'], function () {
    Route::get('student/dashboard', [DashboardController::class, 'studentDashboard'])->name('student-dashboard');
    Route::get('student/subject/{id}', [DashboardController::class, 'studentSubjects'])->name('student-subject');
    Route::post('update-boards', [DashboardController::class, 'updateBoards'])->name('update-boards');
    Route::POST('activate_board', [DashboardController::class,'activeBoard'])->name('activate_board');
    Route::POST('create_order_request',[DashboardController::class,'create_order_request'])->name('create_order_request');
    Route::get('fetch-subscription-panel', [DashboardController::class, 'fetch_subscription_panel']);
    Route::POST('verify_order',[StudentApiController::class,'verify_order']);
    Route::get('fetch-subjects/{id}', [DashboardController::class, 'fetchSubjects'])->name('fetch-subjects');
    Route::POST('fetch-topics', [DashboardController::class, 'fetchTopics'])->name('fetch-topics');
    Route::POST('fetch-question-details', [DashboardController::class, 'question_details'])->name('fetch-question-details');
    Route::get('fetch-question-detail/{id}', [DashboardController::class, 'questionDetails'])->name('fetch-question-detail');
    Route::get('mark-as/{id}/{mark}', [DashboardController::class, 'markAs'])->name('mark-as');
    Route::POST('fetch-question', [DashboardController::class, 'fetch_question'])->name('fetch-question');
});
Route::group(['middleware' => 'admin'], function () {
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('admin/upload', [DashboardController::class, 'upload_files']);
	Route::post('admin/add_file', [DashboardController::class, 'add_file']);
	Route::get('admin/deleteUpload/{id}', [DashboardController::class, 'delete_file']);


    Route::get('admin/fetchbystatus/{token}', [AllQueryController::class, 'fetchbystatus']);
    Route::post('admin/update_change_status', [AllQueryController::class, 'update_change_status']);

    Route::get('admin/pendingqueries', [PendingQueryController::class, 'pendingqueries']);
    Route::get('admin/completedqueries', [CompletedQueryController::class, 'completedqueries']);

    //Student Profile Routes
    Route::get('admin/show_student_profile', [StudentProfileController::class, 'show_student_profile']);
    Route::get('admin/add_student_profilepage', [StudentProfileController::class, 'show_student_profilepage']);
    Route::post('admin/add_student_profile', [StudentProfileController::class, 'save_student_profile']);
    Route::get('admin/update_student_profile/edit/{id}', [StudentProfileController::class, 'edit']);
    Route::put('admin/update_student_profile/{id}', [StudentProfileController::class, 'update_student_profile']);
    Route::get('admin/show_student_profile/delete/{id}', [StudentProfileController::class, 'delete']);

    Route::put('admin/status_change/{id}', [StudentProfileController::class, 'save_status']);

    //Setup Section Routes
    //school section
    Route::get('admin/show_school', [SetupController::class, 'show_school']);
    Route::get('admin/add_schoolpage', [SetupController::class, 'show_schoolpage']);
    Route::post('admin/add_school', [SetupController::class, 'save_school']);
    Route::put('admin/update_school/{id}', [SetupController::class, 'update_school']);
    Route::get('admin/show_school/delete/{id}', [SetupController::class, 'delete_school']);

    //exam section
    Route::get('admin/show_exam', [SetupController::class, 'show_exam']);
    Route::get('admin/add_exampage', [SetupController::class, 'show_exampage']);
    Route::post('admin/add_exam', [SetupController::class, 'save_exam']);
    Route::get('admin/update_exam/edit/{id}', [SetupController::class, 'edit_exam']);
    Route::put('admin/update_exam/{id}', [SetupController::class, 'update_exam']);
    Route::get('admin/show_exam/delete/{id}', [SetupController::class, 'delete_exam']);

    Route::get('admin/add_exam_subjects', [SetupController::class, 'add_exam_subjects']);
    Route::get('select-exam/{id}', [SetupController::class, 'selectExam'])->name('select-exam');
    Route::post('admin/add_examsubjects', [SetupController::class, 'save_examsubjects']);

    Route::put('admin/status_change_exam/{id}', [SetupController::class, 'save_status_exam']);

    //board section
    Route::get('admin/show_board', [SetupController::class, 'show_board']);
    Route::get('admin/add_boardpage', [SetupController::class, 'show_boardpage']);
    Route::post('admin/add_board', [SetupController::class, 'save_board']);
    Route::put('admin/update_board/{id}', [SetupController::class, 'update_board']);
    Route::get('admin/show_board/delete/{id}', [SetupController::class, 'delete_board']);
    //subject section
    Route::get('admin/show_subject', [SetupController::class, 'show_subject']);
    Route::get('admin/add_subjectpage', [SetupController::class, 'show_subjectpage']);
    Route::post('admin/add_subject', [SetupController::class, 'save_subject']);
    Route::put('admin/update_subject/{id}', [SetupController::class, 'update_subject']);
    Route::get('admin/show_subject/delete/{id}', [SetupController::class, 'delete_subject']);

    Route::get('admin/add_exam_subjects_board', [SetupController::class, 'add_exam_subjects_board']);
    Route::post('admin/add_exam_subject_board', [SetupController::class, 'save_examsubjectsboard']);
    //class section
    Route::get('admin/show_class', [SetupController::class, 'show_class']);
    Route::get('admin/add_classpage', [SetupController::class, 'show_classpage']);
    Route::post('admin/add_class', [SetupController::class, 'save_class']);
    Route::put('admin/update_class/{id}', [SetupController::class, 'update_class']);
    Route::get('admin/show_class/delete/{id}', [SetupController::class, 'delete_class']);
    //Country Routes
    Route::get('admin/show_country', [CountryController::class, 'show_country']);
    Route::get('admin/add_countrypage', [CountryController::class, 'show_countrypage']);
    Route::post('admin/add_country', [CountryController::class, 'save_country']);
    Route::put('admin/update_country/{id}', [CountryController::class, 'update_country']);
    Route::get('admin/show_country/delete/{id}', [CountryController::class, 'delete_country']);

    //QuestionSet Routes
    Route::get('admin/show_question_set', [QuestionSetController::class, 'show_question_set']);
    Route::get('admin/add_question_setpage', [QuestionSetController::class, 'show_question_setpage']);
    Route::post('admin/add_question_set', [QuestionSetController::class, 'save_question_set']);
    Route::post('admin/load_ppt', [QuestionSetController::class, 'save_question_set_from_file']);
    Route::get('admin/update_question_set/edit/{id}', [QuestionSetController::class, 'edit']);
    Route::put('admin/update_question_set/{id}', [QuestionSetController::class, 'update_question_set']);
    Route::get('admin/show_question_set/delete/{id}', [QuestionSetController::class, 'delete']);

    //Previous Year Paper Routes
    Route::get('admin/show_previous_year_paper', [PreviousPaperController::class, 'show_previous_year_paper']);
    Route::get('admin/add_previous_year_paperpage', [PreviousPaperController::class, 'show_previous_year_paperpage']);
    Route::post('admin/add_previous_year_paper', [PreviousPaperController::class, 'save_previous_year_paper']);
    Route::get('admin/update_previous_year_paper/edit/{id}', [PreviousPaperController::class, 'edit']);
    Route::put('admin/update_previous_year_paper/{id}', [PreviousPaperController::class, 'update_previous_year_paper']);
    Route::get('admin/show_previous_year_paper/delete/{id}', [PreviousPaperController::class, 'delete']);

    //Revision Notes Routes
    Route::get('admin/show_revision_notes', [RevisionNotesController::class, 'show_revision_notes']);
    Route::get('admin/add_revision_notespage', [RevisionNotesController::class, 'show_revision_notespage']);
    Route::post('admin/add_revision_notes', [RevisionNotesController::class, 'save_revision_notes']);
    Route::get('admin/update_revision_notes/edit/{id}', [RevisionNotesController::class, 'edit']);
    Route::put('admin/update_revision_notes/{id}', [RevisionNotesController::class, 'update_previous_year_paper']);
    Route::get('admin/show_previous_year_paper/delete/{id}', [RevisionNotesController::class, 'delete']);

    //Reset Password
    Route::get('admin/profile', [ProfileController::class, 'profile']);
    Route::post('admin/passwordsave', [ProfileController::class, 'passwordsave']);

    //Subscription Routes

    Route::get('admin/subscription_plan_detail', [SubscriptionController::class, 'subscription_plan_detail']);
    Route::get('admin/add_subscription_plan_detail', [SubscriptionController::class, 'show_subscription_plan_detail']);
    Route::post('admin/add_subscription_plan', [SubscriptionController::class, 'add_subscription']);
    Route::get('admin/update_subscription_plan/edit/{id}', [SubscriptionController::class, 'edit']);
    Route::put('admin/update_subscription_plan/{id}', [SubscriptionController::class, 'update_subscription']);
    Route::get('admin/subscription_plan_detail/delete/{id}', [SubscriptionController::class, 'delete']);

    Route::put('admin/status_change_subs/{id}', [SubscriptionController::class, 'save_status_subs']);


    Route::get('admin/show_video', [VideoController::class, 'show_video']);
    Route::post('admin/save_video', [VideoController::class, 'save_video']);
    Route::get('admin/show_video/edit/{id}', [VideoController::class, 'edit']);
    Route::put('admin/update_video/{id}', [VideoController::class, 'update_video']);
    Route::get('admin/show_video/delete/{id}', [VideoController::class, 'delete']);

    Route::get('admin/show_testimonial', [TestimonialController::class, 'show_testimonial']);
    Route::post('admin/save_testimonial', [TestimonialController::class, 'save_testimonial']);
    Route::get('admin/show_testimonial/edit/{id}', [TestimonialController::class, 'edit']);
    Route::post('admin/update_testimonial', [TestimonialController::class, 'update_testimonial']);
    Route::get('admin/show_testimonial/delete/{id}', [TestimonialController::class, 'delete']);

    Route::get('admin/show_clientlogo', [ClientlogoController::class, 'show_clientlogo']);
    Route::post('admin/save_clientlogo', [ClientlogoController::class, 'save_clientlogo']);
    Route::get('admin/show_clientlogo/edit/{id}', [ClientlogoController::class, 'edit']);
    Route::put('admin/update_clientlogo/{id}', [ClientlogoController::class, 'update_clientlogo']);
    Route::get('admin/show_clientlogo/delete/{id}', [ClientlogoController::class, 'delete']);

    Route::get('admin/send_mail', [MailController::class, 'send_mail']); // send mail route

    Route::get('admin/show_resume', [MailController::class, 'show_resume']);
    Route::post('admin/save_resume', [MailController::class, 'save_resume']);

    Route::get('admin/managestatus', [ManageStatusController::class, 'managestatus']);
    Route::post('admin/save_status', [ManageStatusController::class, 'save_status']);
    Route::get('admin/managestatus/edit/{id}', [ManageStatusController::class, 'edit']);
    Route::post('admin/update_status', [ManageStatusController::class, 'update_status']);
    Route::get('admin/managestatus/delete/{id}', [ManageStatusController::class, 'delete']);

    // Route::get('admin/adminmanagement', [AdminManagementController::class, 'adminmanagement']);
    // Route::post('admin/save_admin_management', [AdminManagementController::class, 'save_admin_management']);
    // Route::get('admin/adminmanagement/edit/{id}', [AdminManagementController::class, 'edit']);
    // Route::post('admin/update_admin_management', [AdminManagementController::class, 'update_admin_management']);
    // Route::get('admin/adminmanagement/delete/{id}', [AdminManagementController::class, 'delete']);

    // Route::get('admin/adminemail', [AdminEmailController::class, 'adminemail']);
    // Route::post('admin/save_adminemail', [AdminEmailController::class, 'save_adminemail']);
    // Route::get('admin/adminemail/edit/{id}', [AdminEmailController::class, 'edit']);
    // Route::post('admin/update_adminemail', [AdminEmailController::class, 'update_adminemail']);
    // Route::get('admin/adminemail/delete/{id}', [AdminEmailController::class, 'delete']);

    // Route::get('admin/emailsubscriber', [EmailSubscriberController::class, 'emailsubscriber']);

    // //------//

    // Route::get('admin/contact', [ContactController::class, 'contact']);

    // Route::get('admin/loan', [LoanController::class, 'loan']);
});
