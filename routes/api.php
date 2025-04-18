<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login',[\App\Http\Controllers\AuthController::class,'login']);
Route::get('authInfo',[\App\Http\Controllers\AuthController::class,'authInfo'])->middleware('auth:student,teacher');

Route::prefix('student')->name('student.')->group(function (){
    Route::post('register',[\App\Http\Controllers\Student\AuthController::class,'register']);
//    Route::post('login',[\App\Http\Controllers\Student\AuthController::class,'login']);
    Route::prefix('resit_password')->group(function (){
        Route::post('/otp',[\App\Http\Controllers\Student\AuthController::class,'sendResitPasswordOTP']);
        Route::post('/check_otp',[\App\Http\Controllers\Student\AuthController::class,'checkOTP']);
        Route::post('/',[\App\Http\Controllers\Student\AuthController::class,'resitPassword']);

    });
    Route::middleware('auth:student')->group(function (){
        Route::post('logout',[\App\Http\Controllers\Student\AuthController::class,'logout']);
//        Route::get('index',[\App\Http\Controllers\Student\AuthController::class,'index']);
        Route::post('update',[\App\Http\Controllers\Student\AuthController::class,'update']);
        Route::prefix('notifications')->group(function (){
            Route::get('/',[\App\Http\Controllers\Student\Notification\NotificationController::class,'index']);
            Route::get('/mark_as_read',[\App\Http\Controllers\Student\Notification\NotificationController::class,'markAsRead']);
        });
        Route::apiResource('communications',\App\Http\Controllers\Student\CommunicationController::class);
        Route::prefix('camp')->group(function (){
            Route::get('/',[\App\Http\Controllers\Student\Camp\CampController::class,'show']);
            Route::get('/getAllJoinReq',[\App\Http\Controllers\Student\Camp\CampController::class,'getAllJoinRequest']);
            Route::get('/{camp}',[\App\Http\Controllers\Student\Camp\CampController::class,'join']);
        });

        Route::prefix('teachers')->group(function (){
            Route::get('/',[\App\Http\Controllers\Student\TeacherController::class,'index']);
            Route::get('/show/{teacher}',[\App\Http\Controllers\Student\TeacherController::class,'show']);
        });

        Route::get('/estimates/{course}',\App\Http\Controllers\Student\EstimatesController::class)
            ->middleware('can:access,course');

        Route::prefix('courses')->group(function (){
            Route::get('/timeline',[\App\Http\Controllers\Student\Course\CourseController::class,'timeline']);
            Route::get('/',[\App\Http\Controllers\Student\Course\CourseController::class,'index']);
            Route::get('/join/{course}',[\App\Http\Controllers\Student\Course\CourseController::class,'join']);
            Route::get('/leave/{course}',[\App\Http\Controllers\Student\Course\CourseController::class,'leave']);
            Route::get('/available',[\App\Http\Controllers\Student\Course\CourseController::class,'available']);
            Route::prefix('{course}')
                ->middleware('can:access,course')
                ->group(function (){
                    Route::get('advertisements',\App\Http\Controllers\Student\Course\Advertisement\AdvertisementController::class);
                    Route::prefix('/lectures')->group(function (){
                        Route::get('/',[\App\Http\Controllers\Student\Course\Lecture\LectureController::class,'index']);
                        Route::get('/{lecture}',[\App\Http\Controllers\Student\Course\Lecture\LectureController::class,'show']);
                        Route::get('/documents/{document}',\App\Http\Controllers\Student\Course\Lecture\Document\DocumentController::class);

                    });
                    Route::prefix('/assignments')->group(function (){
                        Route::get('/',[\App\Http\Controllers\Student\Course\Assignment\AssignmentController::class,'index']);
                        Route::get('/{assignment}',[\App\Http\Controllers\Student\Course\Assignment\AssignmentController::class,'show']);
                        Route::get('/documents/{document}',\App\Http\Controllers\Student\Course\Assignment\Document\DocumentController::class);
                        Route::post('/{assignment}/submit',[\App\Http\Controllers\Student\Course\Assignment\AssignmentController::class,'submit']);
                        Route::get('/{assignment}/submit',[\App\Http\Controllers\Student\Course\Assignment\AssignmentController::class,'showSubmit']);
                        Route::delete('/{assignment}/submit',[\App\Http\Controllers\Student\Course\Assignment\AssignmentController::class,'deleteSubmit']);
                    });
                    Route::prefix('quizzes')->group(function (){
                        Route::get('/',[\App\Http\Controllers\Student\Course\Quiz\QuizController::class,'index']);
                        Route::get('/{quiz}',[\App\Http\Controllers\Student\Course\Quiz\QuizController::class,'show']);
                        Route::get('/{quiz}/attempt',[\App\Http\Controllers\Student\Course\Quiz\QuizController::class,'attempt']);
                        Route::post('/{quiz}/attempt',[\App\Http\Controllers\Student\Course\Quiz\QuizController::class,'submitAttempt']);
                        Route::get('/{quiz}/review',[\App\Http\Controllers\Student\Course\Quiz\QuizController::class,'reviewAttempt']);
                    });

                    Route::prefix('/documents')->group(function (){
                        Route::get('/',[\App\Http\Controllers\Student\Course\Document\DocumentController::class,'index']);
                        Route::get('/{document}',[\App\Http\Controllers\Student\Course\Document\DocumentController::class,'show']);
                    });

                });
        });
    });
});

Route::prefix('teacher')->name('teacher.')->group(function (){
    Route::post('register',[\App\Http\Controllers\Teacher\AuthController::class,'register']);
//    Route::post('login',[\App\Http\Controllers\Teacher\AuthController::class,'login']);
    Route::prefix('resit_password')->group(function (){
        Route::post('/otp',[\App\Http\Controllers\Teacher\AuthController::class,'sendResitPasswordOTP']);
        Route::post('/check_otp',[\App\Http\Controllers\Teacher\AuthController::class,'checkOTP']);
        Route::post('/',[\App\Http\Controllers\Teacher\AuthController::class,'resitPassword']);

    });
    Route::middleware('auth:teacher')->group(function (){
        Route::post('logout',[\App\Http\Controllers\Teacher\AuthController::class,'logout']);
//        Route::get('index',[\App\Http\Controllers\Teacher\AuthController::class,'index']);
        Route::post('update',[\App\Http\Controllers\Teacher\AuthController::class,'update']);
        Route::prefix('notifications')->group(function (){
            Route::get('/',[\App\Http\Controllers\Teacher\Notification\NotificationController::class,'index']);
            Route::get('/mark_as_read',[\App\Http\Controllers\Teacher\Notification\NotificationController::class,'markAsRead']);
        });

        Route::prefix('students')->group(function (){
           Route::get('/',[\App\Http\Controllers\Teacher\StudentController::class,'index']);
           Route::get('/contacts/{student}',[\App\Http\Controllers\Teacher\StudentController::class,'contacts']);
        });

        Route::apiResource('communications',\App\Http\Controllers\Teacher\CommunicationController::class);

        Route::prefix('camps')->group(function (){
            Route::get('/',[\App\Http\Controllers\Teacher\Camp\CampController::class,'index']);
            Route::get('/show/{camp}',[\App\Http\Controllers\Teacher\Camp\CampController::class,'show']);
            Route::get('/join/{camp}',[\App\Http\Controllers\Teacher\Camp\CampController::class,'join']);
            Route::delete('/forget/{camp}',[\App\Http\Controllers\Teacher\Camp\CampController::class,'forget']);
        });
        Route::prefix('/courses/{course}/assignments/{assignment}/submits')->group(function (){
            Route::get('/',[\App\Http\Controllers\Teacher\Course\Assignment\AssignmentController::class,'studentSubmits']);
            Route::get('/students/{student}',[\App\Http\Controllers\Teacher\Course\Assignment\AssignmentController::class,'downloadStudentSubmit']);
            Route::post('/students/{student}',[\App\Http\Controllers\Teacher\Course\Assignment\AssignmentController::class,'rate']);
        });
        Route::get('courses/timeline',[\App\Http\Controllers\Teacher\Course\CourseController::class,'timeLine']);
        Route::apiResource('courses.quizzes.quizAttempt',\App\Http\Controllers\Teacher\Course\Quiz\Attempt\AttemptController::class)
            ->only(['index','show','update']);
        Route::apiResource('courses.quizzes.questions',\App\Http\Controllers\Teacher\Course\Quiz\Question\QuestionController::class);
        Route::apiResource('courses.quizzes',\App\Http\Controllers\Teacher\Course\Quiz\QuizController::class);
        Route::apiResource('courses.assignments.documents', \App\Http\Controllers\Teacher\Course\Assignment\Document\DocumentController::class)
            ->except(['update']);
        Route::apiResource('courses.assignments',\App\Http\Controllers\Teacher\Course\Assignment\AssignmentController::class);
        Route::apiResource('courses.lectures.documents', \App\Http\Controllers\Teacher\Course\Lectuer\Document\DocumentController::class)
            ->except(['update']);
        Route::apiResource('courses.lectures',\App\Http\Controllers\Teacher\Course\Lectuer\LectureController::class);
        Route::apiResource('courses.advertisements',\App\Http\Controllers\Teacher\Course\Advertisement\AdvertisementController::class);
        Route::apiResource('courses.documents', \App\Http\Controllers\Teacher\Course\Document\DocumentController::class)
            ->except(['update']);
        Route::apiResource('courses',\App\Http\Controllers\Teacher\Course\CourseController::class);

        Route::prefix('student-estimates')->group(function (){
            Route::redirect('/courses', route('teacher.courses.index'));
            Route::get('/courses/{course}/students',[\App\Http\Controllers\Teacher\StudentController::class,'courseStudent']);
            Route::get('/courses/{course}/students/{student}',[\App\Http\Controllers\Teacher\StudentController::class,'studentEstimates']);
        });

    });
});

Route::prefix('admin')->name('admin.')->group(function (){
    Route::post('register',[\App\Http\Controllers\Admin\AuthController::class,'register']);
    Route::post('login',[\App\Http\Controllers\Admin\AuthController::class,'login']);
    Route::prefix('resit_password')->group(function (){
        Route::post('/otp',[\App\Http\Controllers\Admin\AuthController::class,'sendResitPasswordOTP']);
        Route::post('/check_otp',[\App\Http\Controllers\Admin\AuthController::class,'checkOTP']);
        Route::post('/',[\App\Http\Controllers\Admin\AuthController::class,'resitPassword']);

    });
    Route::middleware('auth:admin')->group(function (){
        Route::post('logout',[\App\Http\Controllers\Admin\AuthController::class,'logout']);
        Route::get('index',[\App\Http\Controllers\Admin\AuthController::class,'index']);
        Route::post('update',[\App\Http\Controllers\Admin\AuthController::class,'update']);
        Route::prefix('notifications')->group(function (){
            Route::get('/',[\App\Http\Controllers\Admin\Notification\NotificationController::class,'index']);
            Route::get('/mark_as_read',[\App\Http\Controllers\Admin\Notification\NotificationController::class,'markAsRead']);
        });

        Route::apiResource('communications',\App\Http\Controllers\Admin\CommunicationController::class);

        Route::prefix('camps')->group(function (){
            Route::prefix('requests')->group(function (){
                Route::get('/',[\App\Http\Controllers\Admin\Request\RequestController::class,'index']);
                Route::get('/reply/{request}/{status}',[\App\Http\Controllers\Admin\Request\RequestController::class,'reply']);
            });


            Route::prefix("teachers")->group(function (){
                Route::get('/search/{name}',[\App\Http\Controllers\Admin\Teacher\TeacherController::class,'search']);

                Route::get('/courses/{course}',[\App\Http\Controllers\Admin\Course\CourseController::class,'show']);
            });
            Route::apiResource('teachers',\App\Http\Controllers\Admin\Teacher\TeacherController::class)
                ->except(['store']);
            Route::get('students/search/{name}',[\App\Http\Controllers\Admin\Student\StudentController::class,'search']);
            Route::apiResource('students',\App\Http\Controllers\Admin\Student\StudentController::class)
                ->except(['store']);
        });
        Route::apiResource('camps',\App\Http\Controllers\Admin\Camp\CampController::class);

    });
});

Route::prefix('camp')->group(function (){
    Route::get('getAll',[\App\Http\Controllers\Camp\CampController::class,'getAll']);
});


