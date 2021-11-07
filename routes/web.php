<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('auth/login');
});

// Route::get('about-us', [App\Http\Controllers\Student\SomePolicyController::class, 'aboutUs'])->name('about-us');
// Route::get('terms-conditions', [App\Http\Controllers\Student\SomePolicyController::class, 'termsConditions'])->name('terms-conditions');
// Route::get('privacy-policy', [App\Http\Controllers\Student\SomePolicyController::class, 'privacyPolicy'])->name('privacy-policy');
// Route::get('return-policy', [App\Http\Controllers\Student\SomePolicyController::class, 'returnPolicy'])->name('return-policy');

Auth::routes(['verify' => true, 'register' => false]);
// SSLCOMMERZ Start
Route::post('/success', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'paymentFailed']);
Route::post('/cancel', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'paymentCancel']);
//SSLCOMMERZ END

Route::group(['middleware' => 'auth'], function (){
    
    Route::get('courses', [App\Http\Controllers\Student\MasterController::class, 'courses'])->name('courses');
    Route::get('updateRunningCourse', [App\Http\Controllers\Student\MasterController::class, 'updateRunningCourse'])->name('updateRunningCourse');
    
    Route::get('home', [App\Http\Controllers\Student\MasterController::class, 'home'])->name('home');
    Route::group(['middleware' => 'courseStudentFreez'], function (){
        
        Route::get('profile', [App\Http\Controllers\Student\ProfileController::class, 'index'])->name('profile');
        Route::get('changeProfile', [App\Http\Controllers\Student\ProfileController::class, 'changeProfile'])->name('changeProfile');
        Route::post('changeProfileUpdate', [App\Http\Controllers\Student\ProfileController::class, 'changeProfileUpdate'])->name('changeProfileUpdate');
        Route::put('profileUpdate/{id}', [App\Http\Controllers\Student\ProfileController::class, 'updateProfile'])->name('profileUpdate');
        Route::put('profilePassUpdate/{id}', [App\Http\Controllers\Student\ProfileController::class, 'updatePassword'])->name('profilePassUpdate');
        Route::post('notifySeen', [App\Http\Controllers\Student\MasterController::class, 'notifySeen'])->name('notifySeen');
    
        //practice time
        Route::post('parcticeTimeUpdate', [App\Http\Controllers\Student\ProfileController::class, 'updatePracticeTime'])->name('parcticeTimeUpdate');
        // CLASSROOM
        Route::get('overview', [App\Http\Controllers\Student\ClassroomController::class, 'overview'])->name('overview');
        // MY BATCH
        Route::get('studentRanking', [App\Http\Controllers\Student\ClassroomController::class, 'studentRanking'])->name('studentRanking');
        Route::get('stdRankRunningProgress', [App\Http\Controllers\Student\ClassroomController::class, 'stdRankRunningProgress'])->name('stdRankRunningProgress');
        Route::get('todayGoal', [App\Http\Controllers\Student\ClassroomController::class, 'todayGoal'])->name('todayGoal');
        Route::get('classResourceModal', [App\Http\Controllers\Student\ClassroomController::class, 'classResourceModal'])->name('classResourceModal');

        //GROUP STUDY
        Route::get('groupStudyAttendence', [App\Http\Controllers\Student\ClassroomController::class, 'groupStudyAttendence'])->name('groupStudyAttendence');
        Route::post('submitGroupStudyAttendence', [App\Http\Controllers\Student\ClassroomController::class, 'submitGroupStudyAttendence'])->name('submitGroupStudyAttendence');

        // Class Menu
        Route::get('class', [App\Http\Controllers\Student\ClassroomController::class, 'classIndex'])->name('class');
        Route::get('classDetails', [App\Http\Controllers\Student\ClassroomController::class, 'classDetails'])->name('classDetails');
        Route::get('classResource', [App\Http\Controllers\Student\ClassroomController::class, 'classResource'])->name('classResource');
        Route::get('assignments', [App\Http\Controllers\Student\ClassroomController::class, 'assignments'])->name('assignments');
        Route::post('submitAssignment', [App\Http\Controllers\Student\ClassroomController::class, 'submitAssignment'])->name('submitAssignment');
        Route::get('activities', [App\Http\Controllers\Student\ClassroomController::class, 'activities'])->name('activities');
        Route::post('updateVideoWatchTime', [App\Http\Controllers\Student\ClassroomController::class, 'updateVideoWatchTime'])->name('updateVideoWatchTime');
        Route::get('classLectureVideo', [App\Http\Controllers\Student\ClassroomController::class, 'classLectureVideo'])->name('classLectureVideo');
        
        // ASSIGNMENT REVIEW RELATED DISCUSSION FOR STUDENT
        Route::post('stdDiscussionMsgSend', [App\Http\Controllers\Student\ClassroomController::class, 'stdDiscussionMsgSend'])->name('stdDiscussionMsgSend');
        Route::get('stdDiscussionMsgAjax', [App\Http\Controllers\Student\ClassroomController::class, 'stdDiscussionMsgAjax'])->name('stdDiscussionMsgAjax');

        //COMPLAIN ASSIGNMENT REVIEW RELATED FOR STUDENT
        Route::get('assignmentComplain/{submission_id}', [App\Http\Controllers\Student\ClassroomController::class, 'assignmentComplain'])->name('assignmentComplain');
        Route::post('submitAssignmentComplain', [App\Http\Controllers\Student\ClassroomController::class, 'submitAssignmentComplain'])->name('submitAssignmentComplain');

        Route::get('quiz', [App\Http\Controllers\Student\ClassroomController::class, 'quiz'])->name('quiz');                                        
        Route::get('classExamRunning/{batch_class_id}', [App\Http\Controllers\Student\ClassExamController::class, 'classExam'])->name('classExamRunning');
        Route::post('classExamSubmit', [App\Http\Controllers\Student\ClassExamController::class, 'classExamSubmit'])->name('classExamSubmit');
        // end class menu
        Route::resource('takeSupport', App\Http\Controllers\Student\SupportController::class);
        // CLASS REQUEST
        Route::resource('requestClass', App\Http\Controllers\Student\ClassRequestController::class);
        //liveClass
        Route::get('stdLiveClass', [App\Http\Controllers\Student\StdLiveClassController::class, 'stdLiveClass'])->name('stdLiveClass');
        // IMPROVE SCORE 
        Route::get('improveScore', [App\Http\Controllers\Student\ClassroomController::class, 'improveScore'])->name('improveScore');    
        //SUCCESS STORY
        Route::resource('mySuccessStory', App\Http\Controllers\Student\SuccessStoryController::class);
        Route::post('storyReactUpdate', [App\Http\Controllers\Student\MasterController::class, 'storyReactUpdate'])->name('storyReactUpdate');
        Route::get('std-success', [App\Http\Controllers\Student\MasterController::class, 'stdSuccessIndex'])->name('std-success');
        Route::get('stdSuccessListAjax', [App\Http\Controllers\Student\MasterController::class, 'stdSuccessListAjax'])->name('stdSuccessListAjax');
        
        //availabe assignment 
        Route::get('availableAssignment', [App\Http\Controllers\Student\AssignmentReviewController::class, 'availableAssignment'])->name('availableAssignment');
        Route::get('takenAvailableAssignment', [App\Http\Controllers\Student\AssignmentReviewController::class, 'takenAvailableAssignment'])->name('takenAvailableAssignment');
        Route::post('applyAvailableAssignment', [App\Http\Controllers\Student\AssignmentReviewController::class, 'applyAvailableAssignment'])->name('applyAvailableAssignment');

        // STUDENT ASSIGNMENT REVIEW FOR REVIEWER
        Route::get('reviewAssignment', [App\Http\Controllers\Student\AssignmentReviewController::class, 'reviewIndex'])->name('reviewAssignment');
        Route::get('reviewInstruction', [App\Http\Controllers\Student\AssignmentReviewController::class, 'reviewInstruction'])->name('reviewInstruction');
        Route::get('assignmentDetails', [App\Http\Controllers\Student\AssignmentReviewController::class, 'assignmentDetails'])->name('assignmentDetails');
        Route::post('assignmentMarking', [App\Http\Controllers\Student\AssignmentReviewController::class, 'assignmentMarking'])->name('assignmentMarking');
        Route::get('assignmentDiscussion', [App\Http\Controllers\Student\AssignmentReviewController::class, 'assignmentDiscussion'])->name('assignmentDiscussion');
        Route::post('discussionMsgSend', [App\Http\Controllers\Student\AssignmentReviewController::class, 'discussionMsgSend'])->name('discussionMsgSend');
        Route::get('discussionMsgAjax', [App\Http\Controllers\Student\AssignmentReviewController::class, 'discussionMsgAjax'])->name('discussionMsgAjax');
        Route::post('filepondUpload', [App\Http\Controllers\Student\FilePondUploadController::class, 'filepondUpload'])->name('filepondUpload');
        Route::delete('filepondDelete', [App\Http\Controllers\Student\FilePondUploadController::class, 'filepondDelete'])->name('filepondDelete');
        
    });         
    // PAYMENT GATEWAY
    Route::get('myDuePayments', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'myDuePayments'])->name('myDuePayments');
    Route::get('viewInvoice', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'viewInvoice'])->name('viewInvoice');
    Route::get('paymentDetails', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'paymentDetails'])->name('paymentDetails');
    Route::post('paymentNotify', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'paymentNotify'])->name('paymentNotify'); //pay Temp
    // Redirect Route After Payment
    Route::get('paymentFailed', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'paymentFailed'])->name('paymentFailed');
    Route::get('paymentSuccess', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'paymentSuccess'])->name('paymentSuccess');      
    // Route::get('successTest', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'successTest'])->name('successTest');      
    // Route::get('dangerTest', [App\Http\Controllers\Student\SslCommerzPaymentController::class, 'dangerTest'])->name('dangerTest');     
    
});


Route::group(['prefix' => 'provider', 'as'=>'provider.'], function (){

    //Login & Logout
    Route::get('/', ['as'=>'login', function (){ return redirect()->route('provider.login');}]);
    Route::get('login', [App\Http\Controllers\Provider\MasterController::class, 'getLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Provider\MasterController::class, 'postLogin']);
    Route::post('logout', [App\Http\Controllers\Provider\MasterController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'providerAuth'], function (){
        Route::get('home', [App\Http\Controllers\Provider\MasterController::class, 'home'])->name('home');
        Route::get('profile', [App\Http\Controllers\Provider\ProfileController::class, 'index'])->name('profile');
        Route::put('profileUpdate/{id}', [App\Http\Controllers\Provider\ProfileController::class, 'updateProfile'])->name('profileUpdate');
        Route::put('profilePassUpdate/{id}', [App\Http\Controllers\Provider\ProfileController::class, 'updatePassword'])->name('profilePassUpdate');

        Route::resource('widget', App\Http\Controllers\Provider\StudentWidgetController::class);
        Route::resource('notification', App\Http\Controllers\Provider\StudentNotificationController::class);
        // SEND SMS
        Route::resource('sendSms', App\Http\Controllers\Provider\SendSmsController::class);
        Route::resource('eventSms', App\Http\Controllers\Provider\EventSmsController::class);

        //course wise batch
        Route::get('getCourseWiseBatch', [App\Http\Controllers\Provider\AssignBatchController::class, 'getCourseWiseBatch'])->name('getCourseWiseBatch');
        Route::get('getBatchWiseStudents', [App\Http\Controllers\Provider\AssignBatchController::class, 'getBatchWiseStudents'])->name('getBatchWiseStudents');

        Route::resource('supCategory', App\Http\Controllers\Provider\SupportCategoryController::class);

        Route::resource('student', App\Http\Controllers\Provider\StudentController::class);
        Route::resource('teacher', App\Http\Controllers\Provider\TeacherController::class);
        Route::resource('support', App\Http\Controllers\Provider\SupportController::class);
        Route::get('traineeUserLogin', [App\Http\Controllers\Provider\StudentController::class, 'traineeUserLogin'])->name('traineeUserLogin');

        Route::resource('course', App\Http\Controllers\Provider\CourseController::class);
        Route::resource('courseAddClass', App\Http\Controllers\Provider\ClassController::class);
        Route::resource('courseAssignmentArchive', App\Http\Controllers\Provider\ArchiveAssignmentController::class);
        Route::resource('courseQuestionArchive', App\Http\Controllers\Provider\ArchiveQuestionController::class);
        Route::resource('batch', App\Http\Controllers\Provider\AssignBatchController::class);
        //Update Schedule
        Route::get('updateSchedule/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'schedule'])->name('updateSchedule');
        Route::post('updateSchedule/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'updateSchedule'])->name('updateSchedule');
        // Assign Teacher
        Route::get('assignTeacher/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'assignTeacher'])->name('assignTeacher');
        Route::post('assignTeacher/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'updateTeacher'])->name('assignTeacher');
        //ASSIGN INSTRUCTOR
        Route::get('assignInstructor/{batch_id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'assignInstructor'])->name('assignInstructor');
        Route::post('assignInstructor/{batch_id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'updateInstructor'])->name('assignInstructor');

        // Assign Captain
        Route::get('assignCaptain/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'assignCaptain'])->name('assignCaptain');
        Route::post('assignCaptain/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'updateCaptain'])->name('updateCaptain');

        //BATCH COMPLETE
        Route::get('batchComplete/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'batchComplete'])->name('batchComplete');
        Route::post('batchCompleteAction/{id}', [App\Http\Controllers\Provider\AssignBatchController::class, 'batchCompleteAction'])->name('batchCompleteAction');
        //Add Assign Batch Class
        Route::get('assignBatchList', [App\Http\Controllers\Provider\AssignBatchController::class, 'assignBatchList'])->name('assignBatchList');
        Route::resource('batchAddClass', App\Http\Controllers\Provider\BatchAddClassController::class);
        // Show and Update Remark of Attendant Student
        Route::get('batchShowAttendence', [App\Http\Controllers\Provider\BatchAddClassController::class, 'showAttendence'])->name('batchShowAttendence');
        Route::get('batchAttendenceRemark', [App\Http\Controllers\Provider\BatchAddClassController::class, 'attendenceRemark'])->name('batchAttendenceRemark');
        Route::post('batchSaveAttendenceRemark', [App\Http\Controllers\Provider\BatchAddClassController::class, 'saveAttendenceRemark'])->name('batchSaveAttendenceRemark');
        
        Route::resource('assignStudent', App\Http\Controllers\Provider\AssignStudentController::class);
        
        // student Progress
        Route::get('stdProgress', [App\Http\Controllers\Provider\StudentProgressController::class, 'stdProgress'])->name('stdProgress');
        Route::post('saveStdProgress', [App\Http\Controllers\Provider\StudentProgressController::class, 'saveStdProgress'])->name('saveStdProgress');

        //Analysis
        Route::get('analysisActivity', [App\Http\Controllers\Provider\AnalysisActivityController::class, 'index'])->name('analysisActivity');
        Route::get('analysisBatchStudents', [App\Http\Controllers\Provider\AnalysisActivityController::class, 'batchStudents'])->name('analysisBatchStudents');
        Route::get('remarkUpdate/{student_id}/{batch_id}', [App\Http\Controllers\Provider\AnalysisActivityController::class, 'remarkUpdate'])->name('remarkUpdate');
        Route::post('remarkUpdate/{student_id}/{batch_id}', [App\Http\Controllers\Provider\AnalysisActivityController::class, 'remarkUpdateAction'])->name('remarkUpdateAction');

        // STUDENT PAYMENTS
        Route::get('stdPayment', [App\Http\Controllers\Provider\StudentPaymentController::class, 'index'])->name('stdPayment');
        //STUDENT PAYMENT
        Route::get('updatePayment', [App\Http\Controllers\Provider\StudentPaymentController::class, 'updatePayment'])->name('updatePayment');
        Route::post('updatePaymentAction', [App\Http\Controllers\Provider\StudentPaymentController::class, 'updatePaymentAction'])->name('updatePaymentAction');
        //STUDENT COURSE ACCOUNT FREEZ
        Route::get('courseFreez', [App\Http\Controllers\Provider\StudentPaymentController::class, 'courseFreez'])->name('courseFreez');
        Route::post('courseFreezAction', [App\Http\Controllers\Provider\StudentPaymentController::class, 'courseFreezAction'])->name('courseFreezAction');
        // PAYMENT HISTORY
        Route::get('stdPaymentHistory', [App\Http\Controllers\Provider\StudentPaymentController::class, 'paymentHistory'])->name('stdPaymentHistory');
        Route::get('stdPaymentManual', [App\Http\Controllers\Provider\StudentPaymentController::class, 'stdPaymentManual'])->name('stdPaymentManual');
        Route::post('stdPaymentManualAction', [App\Http\Controllers\Provider\StudentPaymentController::class, 'stdPaymentManualAction'])->name('stdPaymentManualAction');
        // STUDENT MARK UPDATE POWER MENU
        Route::get('powerMenuStdList', [App\Http\Controllers\Provider\StdMarkUpdateController::class, 'index'])->name('powerMenuStdList');
        Route::get('powerMenuStdClassList', [App\Http\Controllers\Provider\StdMarkUpdateController::class, 'stdClassList'])->name('powerMenuStdClassList');
        Route::get('stdClassMarkUpdate', [App\Http\Controllers\Provider\StdMarkUpdateController::class, 'stdClassMarkUpdate'])->name('stdClassMarkUpdate');
        Route::post('stdClassMarkAction', [App\Http\Controllers\Provider\StdMarkUpdateController::class, 'stdClassMarkAction'])->name('stdClassMarkAction');
        // STUDENT SUCCESS STORY APPROVAL
        Route::get('stdSuccessStoryList', [App\Http\Controllers\Provider\StdSuccessStoryController::class, 'index'])->name('stdSuccessStoryList');
        Route::get('stdSuccessStoryApproval', [App\Http\Controllers\Provider\StdSuccessStoryController::class, 'storyApproval'])->name('stdSuccessStoryApproval');
        Route::post('stdSuccessStoryApprovalAction', [App\Http\Controllers\Provider\StdSuccessStoryController::class, 'storyApprovalAction'])->name('stdSuccessStoryApprovalAction');

    });
});

Route::group(['prefix' => 'teacher', 'as'=>'teacher.'], function (){

    //Login & Logout  
    Route::get('/', ['as'=>'login', function (){ return redirect()->route('teacher.login');}]);
    Route::get('login', [App\Http\Controllers\Teacher\MasterController::class, 'getLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Teacher\MasterController::class, 'postLogin']);
    Route::post('logout', [App\Http\Controllers\Teacher\MasterController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'teacherAuth'], function (){
        Route::get('home', [App\Http\Controllers\Teacher\MasterController::class, 'home'])->name('home');
        // DASHBOARD
        Route::get('dashboard', [App\Http\Controllers\Teacher\MasterController::class, 'dashboard'])->name('dashboard');
        Route::get('classAssignment/{assign_batch_class_id}', [App\Http\Controllers\Teacher\MasterController::class, 'classAssignment'])->name('classAssignment');
        Route::get('viewSubmitAssignment/{submission_id}', [App\Http\Controllers\Teacher\MasterController::class, 'viewSubmitAssignment'])->name('viewSubmitAssignment');
        Route::get('viewReviwerSubmitAssignment/{submission_id}', [App\Http\Controllers\Teacher\MasterController::class, 'viewReviwerSubmitAssignment'])->name('viewReviwerSubmitAssignment');
        Route::get('classVideos/{class_id}', [App\Http\Controllers\Teacher\MasterController::class, 'classVideos'])->name('classVideos');
        //update complain assignment mark   
        Route::get('complainAssignmentMark', [App\Http\Controllers\Teacher\MasterController::class, 'complainAssignmentMark'])->name('complainAssignmentMark');
        Route::post('updateAssignmentMark', [App\Http\Controllers\Teacher\MasterController::class, 'updateAssignmentMark'])->name('updateAssignmentMark');

        Route::get('profile', [App\Http\Controllers\Teacher\ProfileController::class, 'index'])->name('profile');

        Route::resource('widget', App\Http\Controllers\Teacher\StuedentWidgetController::class);
        // Teacher Zoom Account
        Route::get('teacherZoomAcc', [App\Http\Controllers\Teacher\TZoomAccountController::class, 'teacherZoomAcc'])->name('teacherZoomAcc');
        Route::post('saveTeacherZoomAcc', [App\Http\Controllers\Teacher\TZoomAccountController::class, 'saveTeacherZoomAcc'])->name('saveTeacherZoomAcc');


        Route::put('profileUpdate/{id}', [App\Http\Controllers\Teacher\ProfileController::class, 'updateProfile'])->name('profileUpdate');
        Route::put('profilePassUpdate/{id}', [App\Http\Controllers\Teacher\ProfileController::class, 'updatePassword'])->name('profilePassUpdate');
    
        //Assigned Batch   
        Route::get('assignedBatch', [App\Http\Controllers\Teacher\AssignedBatchController::class, 'assignedBatch'])->name('assignedBatch');
        Route::resource('assignedBatchClass', App\Http\Controllers\Teacher\AssignedClassController::class);
        
        Route::get('classStatus/{class_id}/{batch_id}', [App\Http\Controllers\Teacher\AssignedClassController::class, 'classStatus'])->name('classStatus');
        Route::post('updateClassStatus/{class_id}/{batch_id}', [App\Http\Controllers\Teacher\AssignedClassController::class, 'updateClassStatus'])->name('updateClassStatus');
        
        //CLASS DRIVE LINK
        Route::get('classDriveLink/{assign_batch_class_id}/{batch_id}', [App\Http\Controllers\Teacher\AssignedClassController::class, 'classDriveLink'])->name('classDriveLink');
        Route::post('updateClassDriveLink/{assign_batch_class_id}/{batch_id}', [App\Http\Controllers\Teacher\AssignedClassController::class, 'updateClassDriveLink'])->name('updateClassDriveLink');
        
        // Take Attendance
        Route::get('batchstuAttendence', [App\Http\Controllers\Teacher\StudentAttendenceController::class, 'index'])->name('batchstuAttendence');
        Route::get('batchstuClassList/{batch_id}', [App\Http\Controllers\Teacher\StudentAttendenceController::class, 'classList'])->name('batchstuClassList');
        Route::get('batchstuGiveAttendence', [App\Http\Controllers\Teacher\StudentAttendenceController::class, 'giveAttendence'])->name('batchstuGiveAttendence');
        Route::post('batchstuSaveAttendence', [App\Http\Controllers\Teacher\StudentAttendenceController::class, 'saveAttendence'])->name('batchstuSaveAttendence');
        // show attendence 
        Route::get('showAttendence', [App\Http\Controllers\Teacher\StudentAttendenceController::class, 'showAttendence'])->name('showAttendence');
        
        // Give Assignment
        Route::resource('batchstuAssignments', App\Http\Controllers\Teacher\AssignmentController::class);
        
        // Assignment 
        Route::get('batchstuStudentList', [App\Http\Controllers\Teacher\AssignmentSubmitStudentController::class, 'index'])->name('batchstuStudentList');
        Route::get('batchstuStudentGiveMark', [App\Http\Controllers\Teacher\AssignmentSubmitStudentController::class, 'batchstuStudentGiveMark'])->name('batchstuStudentGiveMark');
        Route::post('batchstuStudentGiveMarkSave', [App\Http\Controllers\Teacher\AssignmentSubmitStudentController::class, 'batchstuStudentGiveMarkSave'])->name('batchstuStudentGiveMarkSave');
        Route::get('viewSubmissionDetails', [App\Http\Controllers\Teacher\AssignmentSubmitStudentController::class, 'viewSubmissionDetails'])->name('viewSubmissionDetails');
        Route::get('assignmentThrow', [App\Http\Controllers\Teacher\AssignmentController::class, 'assignmentThrow'])->name('assignmentThrow');

        //Class wise Exam
        Route::get('classExamBatch', [App\Http\Controllers\Teacher\ClassExamController::class, 'index'])->name('classExamBatch');
        Route::get('classExamBatchClassList/{batch_id}', [App\Http\Controllers\Teacher\ClassExamController::class, 'batchClassList'])->name('classExamBatchClassList');
        Route::get('classExamConfig', [App\Http\Controllers\Teacher\ClassExamController::class, 'examConfig'])->name('classExamConfig');
        Route::post('classExamConfig', [App\Http\Controllers\Teacher\ClassExamController::class, 'saveExamConfig'])->name('classExamConfig');

        //class exam result
        Route::get('classExamResult', [App\Http\Controllers\Teacher\ClassExamController::class, 'examResult'])->name('classExamResult');
        Route::get('studentResult', [App\Http\Controllers\Teacher\ClassExamController::class, 'examResultShow'])->name('studentResult');

        // REQUESTED CLASS
        Route::get('stdRequestClass', [App\Http\Controllers\Teacher\StdClassRequestController::class, 'index'])->name('stdRequestClass');
        Route::get('stdRequestClassFeeback', [App\Http\Controllers\Teacher\StdClassRequestController::class, 'requestFeedback'])->name('stdRequestClassFeeback');
        Route::put('stdRequestClassFeebackAction', [App\Http\Controllers\Teacher\StdClassRequestController::class, 'requestFeebackAction'])->name('stdRequestClassFeebackAction');
        
        // IMPROVE ASSIGNMENTS
        Route::get('improveAssignment', [App\Http\Controllers\Teacher\ImproveAssignmentController::class, 'index'])->name('improveAssignment');
        Route::get('improveAssignmentMark', [App\Http\Controllers\Teacher\ImproveAssignmentController::class, 'improveAssignmentMark'])->name('improveAssignmentMark');
        Route::post('improveAssignmentMarkSave', [App\Http\Controllers\Teacher\ImproveAssignmentController::class, 'improveAssignmentMarkSave'])->name('improveAssignmentMarkSave');
        
        // Route::get('allAssignmentThrow/{batch_id}', [App\Http\Controllers\Teacher\AssignedBatchController::class, 'allAssignmentThrow'])->name('allAssignmentThrow');

        // STUDENT'S DETAILS REPORT
        Route::get('studentReportForm', [App\Http\Controllers\Teacher\StudentReportController::class, 'index'])->name('studentReportForm');
        Route::post('studentReportOverview', [App\Http\Controllers\Teacher\StudentReportController::class, 'studentReportOverview'])->name('studentReportOverview');
        Route::get('studentReportPrint', [App\Http\Controllers\Teacher\StudentReportController::class, 'studentReportPrint'])->name('studentReportPrint');
        Route::get('getBatchStudents', [App\Http\Controllers\Teacher\StudentReportController::class, 'getBatchStudents'])->name('getBatchStudents');
    });
});

Route::group(['prefix' => 'support', 'as'=>'support.'], function (){

    //Login & Logout
    Route::get('/', ['as'=>'login', function (){ return redirect()->route('support.login');}]);
    Route::get('login', [App\Http\Controllers\Support\MasterController::class, 'getLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Support\MasterController::class, 'postLogin']);
    Route::post('logout', [App\Http\Controllers\Support\MasterController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'supportAuth'], function (){
        Route::get('home', [App\Http\Controllers\Support\MasterController::class, 'home'])->name('home');
        Route::get('profile', [App\Http\Controllers\Support\ProfileController::class, 'index'])->name('profile');

        // Support Zoom Account
        Route::get('supportZoomAcc', [App\Http\Controllers\Support\SZoomAccountController::class, 'supportZoomAcc'])->name('supportZoomAcc');
        Route::post('saveSupportZoomAcc', [App\Http\Controllers\Support\SZoomAccountController::class, 'saveSupportZoomAcc'])->name('saveSupportZoomAcc');

        Route::get('stdRequest', [App\Http\Controllers\Support\StdRequestController::class, 'index'])->name('stdRequest');
        Route::get('requestDetails', [App\Http\Controllers\Support\StdRequestController::class, 'requestDetails'])->name('requestDetails');
        //SUPPORT REQUEST SCHEDULE
        Route::get('stdRequestSchedule', [App\Http\Controllers\Support\StdRequestController::class, 'stdRequestSchedule'])->name('stdRequestSchedule');
        Route::post('stdRequestScheduleAction', [App\Http\Controllers\Support\StdRequestController::class, 'stdRequestScheduleAction'])->name('stdRequestScheduleAction');

        // TAKEN LIST FOR STUDENT ASSIGNMENT REVIEW
        Route::get('checkAssignmentList', [App\Http\Controllers\Support\CheckAssignmentController::class, 'checkAssignmentList'])->name('checkAssignmentList');
        Route::get('checkTakeStdAssignments', [App\Http\Controllers\Support\CheckAssignmentController::class, 'takeStdAssignments'])->name('checkTakeStdAssignments');
        Route::get('getRunningBatch', [App\Http\Controllers\Support\CheckAssignmentController::class, 'getRunningBatch'])->name('getRunningBatch');
        Route::get('getAvailableAssignments', [App\Http\Controllers\Support\CheckAssignmentController::class, 'getAvailableAssignments'])->name('getAvailableAssignments');
        Route::post('takeStdAssignmentAction', [App\Http\Controllers\Support\CheckAssignmentController::class, 'takeStdAssignmentAction'])->name('takeStdAssignmentAction');
        // STUDENT ASSIGNMENT REVIEW
        Route::get('checkReviewAssignment', [App\Http\Controllers\Support\CheckAssignmentController::class, 'reviewIndex'])->name('checkReviewAssignment');
        Route::get('checkReviewInstruction', [App\Http\Controllers\Support\CheckAssignmentController::class, 'reviewInstruction'])->name('checkReviewInstruction');
        Route::get('checkAssignmentDetails', [App\Http\Controllers\Support\CheckAssignmentController::class, 'assignmentDetails'])->name('checkAssignmentDetails');
        Route::post('checkAssignmentMarking', [App\Http\Controllers\Support\CheckAssignmentController::class, 'assignmentMarking'])->name('checkAssignmentMarking');
        Route::get('checkAssignmentDiscussion', [App\Http\Controllers\Support\CheckAssignmentController::class, 'assignmentDiscussion'])->name('checkAssignmentDiscussion');
        Route::post('discussionMsgSend', [App\Http\Controllers\Support\CheckAssignmentController::class, 'discussionMsgSend'])->name('discussionMsgSend');
        Route::get('discussionMsgAjax', [App\Http\Controllers\Support\CheckAssignmentController::class, 'discussionMsgAjax'])->name('discussionMsgAjax');
        
        Route::get('viewStdComplain', [App\Http\Controllers\Support\CheckAssignmentController::class, 'viewStdComplain'])->name('viewStdComplain');
        Route::post('updateComplaintMark', [App\Http\Controllers\Support\CheckAssignmentController::class, 'updateComplaintMark'])->name('updateComplaintMark');
    });
});
