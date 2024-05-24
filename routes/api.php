<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Auth\RegisterController;
use App\Http\Controllers\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\v1\Auth\ResetPasswordController;
use App\Http\Controllers\v1\Auth\VerifyAccountController;
use App\Http\Controllers\v1\Profile\ProfileController;
use App\Http\Controllers\v1\Auth\LogoutController;
use App\Http\Controllers\v1\StudiesController;
use App\Http\Controllers\v1\TermsController;
use App\Http\Controllers\v1\SubjectsController;
use App\Http\Controllers\v1\ExamsController;
use App\Http\Controllers\v1\QuestionsController;
use App\Http\Controllers\v1\AnswersController;
use App\Http\Controllers\v1\AttemptedExamController;
use App\Http\Controllers\v1\MyLearningController;
use App\Http\Controllers\v1\PagesController;
use App\Http\Controllers\v1\ContactsController;
use App\Http\Controllers\v1\SettingsController;
use App\Http\Controllers\v1\OtpController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return [
        'app' => 'Exam API',
        'version' => '1.0.0',
    ];
});

Route::prefix('/v1')->group(function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('auth/login', [AuthController::class, 'login']);
        Route::post('auth/adminLogin', [AuthController::class, 'adminLogin']);
        Route::post('auth/create_account', [RegisterController::class, 'create_account']);
        Route::post('auth/create_admin_account', [RegisterController::class, 'create_admin_account']);
        Route::post('auth/verifyEmailForReset', [AuthController::class, 'verifyEmailForReset']);
        Route::get('users/get_admin', [ProfileController::class, 'get_admin']);

        Route::post('uploadImage', [ProfileController::class, 'uploadImage']);
    });

    // Main Admin Routes
    Route::group(['middleware' => ['admin_auth', 'jwt.auth']], function () {
        Route::post('auth/admin_logout', [LogoutController::class, 'logout']);

        Route::get('dashboard/getDashboard', [AttemptedExamController::class, 'getDashboard']);

        Route::get('auth/getMyAccount', [ProfileController::class, 'me']);
        Route::post('user/update', [ProfileController::class, 'update']);
        Route::get('auth/getAllStudents', [AuthController::class, 'getAllStudents']);

         // Studies Routes
         Route::post('studies/save',[StudiesController::class, 'save'] );
         Route::post('studies/getById', [StudiesController::class, 'getById']);
         Route::post('studies/getInfoById', [StudiesController::class, 'getInfoById']);
         Route::get('studies/getAll', [StudiesController::class, 'getAll']);
         Route::post('studies/update', [StudiesController::class, 'update']);
         Route::post('studies/destroy', [StudiesController::class, 'delete']);

        // terms Routes
         Route::post('terms/save',[TermsController::class, 'save'] );
         Route::post('terms/getById', [TermsController::class, 'getById']);
         Route::post('terms/getInfoById', [TermsController::class, 'getInfoById']);
         Route::get('terms/getAll', [TermsController::class, 'getAll']);
         Route::post('terms/update', [TermsController::class, 'update']);
         Route::post('terms/destroy', [TermsController::class, 'delete']);
         Route::post('terms/getByStudy', [TermsController::class, 'getByStudy']);

         // subjects Routes
         Route::post('subjects/save',[SubjectsController::class, 'save'] );
         Route::post('subjects/getById', [SubjectsController::class, 'getById']);
         Route::post('subjects/getInfoById', [SubjectsController::class, 'getInfoById']);
         Route::get('subjects/getAll', [SubjectsController::class, 'getAll']);
         Route::post('subjects/update', [SubjectsController::class, 'update']);
         Route::post('subjects/destroy', [SubjectsController::class, 'delete']);
         Route::post('subjects/getSubjectsFromStudiesAndTerms', [SubjectsController::class, 'getSubjectsFromStudiesAndTerms']);

         // exams Routes
         Route::post('exams/save',[ExamsController::class, 'save'] );
         Route::post('exams/getById', [ExamsController::class, 'getById']);
         Route::get('exams/getAll', [ExamsController::class, 'getAll']);
         Route::post('exams/update', [ExamsController::class, 'update']);
         Route::post('exams/destroy', [ExamsController::class, 'delete']);
         Route::post('exams/sendNotification', [ExamsController::class, 'sendNotification']);

         // setting routes
         Route::post('settings/save',[SettingsController::class, 'save'] );
         Route::post('settings/getById', [SettingsController::class, 'getById']);
         Route::get('settings/getAll', [SettingsController::class, 'getAll']);
         Route::post('settings/update', [SettingsController::class, 'update']);
         Route::post('settings/destroy', [SettingsController::class, 'delete']);


         // exams Routes
         Route::post('learning/save',[MyLearningController::class, 'save'] );
         Route::post('learning/getById', [MyLearningController::class, 'getById']);
         Route::get('learning/getAll', [MyLearningController::class, 'getAll']);
         Route::post('learning/update', [MyLearningController::class, 'update']);
         Route::post('learning/destroy', [MyLearningController::class, 'delete']);



         // questions Routes
         Route::post('questions/save',[QuestionsController::class, 'save'] );
         Route::post('questions/getById', [QuestionsController::class, 'getById']);
         Route::get('questions/getAll', [QuestionsController::class, 'getAll']);
         Route::post('questions/update', [QuestionsController::class, 'update']);
         Route::post('questions/destroy', [QuestionsController::class, 'delete']);


         // answers Routes
         Route::post('answers/save',[AnswersController::class, 'save'] );
         Route::post('answers/getById', [AnswersController::class, 'getById']);
         Route::get('answers/getAll', [AnswersController::class, 'getAll']);
         Route::post('answers/update', [AnswersController::class, 'update']);
         Route::post('answers/destroy', [AnswersController::class, 'delete']);

        // Pages Routes
        Route::post('pages/save', [PagesController::class,'save']);
        Route::post('pages/update', [PagesController::class,'update']);
        Route::post('pages/getById', [PagesController::class, 'getById']);
        Route::post('pages/delete', [PagesController::class,'delete']);
        Route::get('pages/getAll', [PagesController::class,'getAll']);

        Route::get('contacts/getAll',[ContactsController::class, 'getAll'] );
        Route::post('contacts/update',[ContactsController::class, 'update'] );
        Route::post('mails/replyContactForm',[ContactsController::class, 'replyContactForm']);

        Route::post('attemptExam/getUserInfo', [AttemptedExamController::class, 'getUserInfo']);
    });


    // User Routes
    Route::group(['middleware' => ['jwt', 'jwt.auth']], function () {
        Route::post('auth/logout', [LogoutController::class, 'logout']);
        Route::post('profile/update', [ProfileController::class, 'update']);
        Route::post('profile/getByID', [ProfileController::class,'getByID']);

        Route::post('subjects/getMySubjects', [SubjectsController::class, 'getMySubjects']);
        Route::post('exams/getMyExamList', [ExamsController::class, 'getMyExamList']);
        Route::post('exams/getByExamId', [QuestionsController::class, 'getByExamId']);
        Route::post('answers/getMyResult', [AnswersController::class, 'getMyResult']);

        // answers Routes
        Route::post('attemptExam/save',[AttemptedExamController::class, 'save'] );
        Route::post('attemptExam/getById', [AttemptedExamController::class, 'getById']);
        Route::get('attemptExam/getAll', [AttemptedExamController::class, 'getAll']);
        Route::post('attemptExam/update', [AttemptedExamController::class, 'update']);
        Route::post('attemptExam/destroy', [AttemptedExamController::class, 'delete']);
        Route::post('attemptExam/getMyStats', [AttemptedExamController::class, 'getMyStats']);

        Route::post('learning/getById', [MyLearningController::class, 'getById']);
        Route::post('learning/getMyLearningList', [MyLearningController::class, 'getMyLearningList']);

        Route::post('contacts/create',[ContactsController::class, 'save'] );
        Route::post('sendMailToAdmin',[ContactsController::class, 'sendMailToAdmin']);

        Route::post('password/updateUserPasswordWithEmail', [ProfileController::class, 'updateUserPasswordWithEmail']);
    });

    Route::get('studies/getActive', [StudiesController::class, 'getActive']);
    Route::post('terms/getTermsByStudiesId', [TermsController::class, 'getTermsByStudiesId']);

    Route::post('exams/getExamStats', [ExamsController::class, 'getExamStats']);
    Route::post('pages/getContent', [PagesController::class, 'getById']);
    Route::get('settings/getDefault', [SettingsController::class, 'getDefault']);
    Route::post('otp/verifyOTPReset',[OtpController::class, 'verifyOTPReset'] );
});
