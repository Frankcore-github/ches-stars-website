<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'user' => 'API\UserController',
    'staff' => 'API\StaffController', 
    'student' => 'API\StudentController',
    'event' => 'API\EventController',
    'notice' => 'API\NoticeController',
    'sslc' => 'API\SSLCController',
    'teacher' => 'API\ClassTeacherController',
    'gallery' => 'API\GalleryController',
    'newsfeed' => 'API\NewsFeedController',
    'subjectlist' => 'API\SubjectListController',
    'note' => 'API\NoteController',
    'comment' => 'API\CommentController',
    'assignment' => 'API\AssignmentController',
    'dashboard' => 'API\DashboardController',
    'main' => 'API\MainController',
    'allclass' => 'API\AllClassController',
    'staffexam' => 'API\StaffExamController',
    'studentexam' => 'API\StudentExamController',
    'attendance' => 'API\AttendanceController',
    'vote' => 'API\StudentVoteController',
]);
Route::get('admindashboard','API\DashboardController@adminDashboard');
Route::get('staffdashboard','API\DashboardController@staffDashboard');
Route::get('studentdashboard','API\DashboardController@studentDashboard');
Route::get('viewstudents/{class}', 'API\StudentController@viewStudents');
Route::get('showsubjects', 'API\SubjectListController@showSubjects');
Route::get('viewsubjects/{class}', 'API\SubjectListController@viewSubjects');
Route::get('showclassesandsubjects', 'API\StaffController@showClassesAndSubjects');
Route::get('subjects/{class}', 'API\StaffController@subjects');
Route::get('showclasses', 'API\StaffController@showClasses');
Route::get('getStaffClasses/{id}', 'API\StaffController@getStaffClasses');
Route::get('getStaffSubjects/{id}/{classname}', 'API\StaffController@getStaffSubjects');
Route::get('staffNotes/{id}/{classname}/{subjectname}', 'API\StaffController@getStaffNotes');
Route::get('allstaff', 'API\StaffController@allStaff');
Route::get('getvotes/{class}', 'API\StudentVoteController@getVotes');
Route::get('shownotes/{class}/{subject}/{val?}','API\NoteController@showNotes'); 

Route::get('staffquestionpaper/{classname}/{subjectname}', 'API\StaffExamController@showQuestionPapers');
Route::get('staffanswerpaper/{classname}/{subjectname}', 'API\StaffExamController@showStudentPapers');
Route::get('questionpaper/{subjectname}', 'API\StudentExamController@showQuestionPapers');
Route::get('studentanswerpaper/{classname}/{subjectname}', 'API\StudentExamController@showAnswerPapers');

Route::get('markasread/{id}','API\NoteController@markAsRead');
Route::get('markallasread','API\NoteController@markAllAsRead');
Route::get('shownotifications','API\NoteController@showNotifications');
Route::get('studentnotes/{subject}','API\NoteController@studentNotes');
Route::get('profileimage', 'API\UserController@profileImage');
Route::get('notescount/{class}/{subject}', 'API\NoteController@notesCount');

Route::get('selfassignment/{subject}', 'API\AssignmentController@selfAssignment');
Route::get('showassignment/{class}/{subject}', 'API\AssignmentController@showAssignment');  


Route::post('setattendance/{subject}/{noteid}', 'API\AttendanceController@setAttendance'); 
Route::get('countattendance/{studentid}/{subject}/{classname}', 'API\AttendanceController@countAttendance'); 

Route::get('commentnote/{id}', 'API\CommentController@showNote');
Route::get('commentassignment/{id}', 'API\CommentController@showAssignment');
//Main 
Route::get('homeacademics', 'API\MainController@academics');
Route::get('homegallery', 'API\MainController@gallery');
Route::get('homeabout', 'API\MainController@about');
Route::get('homestaff', 'API\MainController@staff');
Route::get('home', 'API\MainController@home');


//search

Route::get('searchstudent', 'API\DashboardController@searchStudent');  
Route::get('searchsubject', 'API\DashboardController@searchSubject');
Route::get('searchstaff', 'API\DashboardController@searchStaff');
Route::get('searchuser', 'API\DashboardController@searchUser');
Route::get('searchexam', 'API\DashboardController@searchExam');