<?php

use App\Http\Controllers\Api\Main\HomePageController;
use App\Http\Controllers\Api\Main\NavBarController;
use App\Http\Controllers\Api\Stack\Main\StackAnswerController;
use Illuminate\Support\Facades\Route;

//Admin panel controllers
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\PlanController;
use App\Http\Controllers\Api\Admin\PostController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\RegulationController;
use App\Http\Controllers\Api\Admin\SubCategoryController;
use App\Http\Controllers\Api\Admin\AdminCommentController;
use App\Http\Controllers\Api\Admin\AdminAnalysisController;
use App\Http\Controllers\Api\Admin\UserFormRequestController;
use App\Http\Controllers\Api\Admin\Auth\ForgetPasswordController;
use App\Http\Controllers\Api\Admin\Auth\EmailVerifyController;


//Client-side controllers
use App\Http\Controllers\Api\Main\CommentLikeController;
use App\Http\Controllers\Api\Main\FundamentalController;
use App\Http\Controllers\Api\Main\AnalysisController;
use App\Http\Controllers\Api\Main\CommentController ;
use App\Http\Controllers\Api\Main\PostLikeController;
use App\Http\Controllers\Api\Main\GetTagController;
use App\Http\Controllers\Api\Main\PostDetailsController;
use App\Http\Controllers\Api\Main\AnalysisLikeController;
use App\Http\Controllers\Api\Main\ProductDetailsController;
use App\Http\Controllers\Api\Main\AnalysisCommentController;
use App\Http\Controllers\Api\Main\CategoryDetailsController;
use App\Http\Controllers\Api\Main\FundamentalLikeController;
use App\Http\Controllers\Api\Main\UserPanel\PanelController;
use App\Http\Controllers\Api\Main\RegulationDetailsController;
use App\Http\Controllers\Api\Main\UserPanel\RequestFormController;

//Stack client-side controllers
use App\Http\Controllers\Api\Stack\Main\StackHomeController;
use App\Http\Controllers\Api\Stack\Main\StackCommentController;
use App\Http\Controllers\Api\Stack\Main\StackQuestionController;
use App\Http\Controllers\Api\Stack\Main\StackUserPanelController;
use App\Http\Controllers\Api\Stack\Main\StackLikeController;
use App\Http\Controllers\Api\Stack\Main\StackApproveAnswerController;
use App\Http\Controllers\Api\Stack\Main\StackQuestionCategoryController;


//Stack admin controllers
use App\Http\Controllers\Api\Stack\Admin\StackApproveController;
use App\Http\Controllers\Api\Stack\Admin\StackCategoryController;
use App\Http\Controllers\Api\Stack\Admin\StackDashboardController;
use App\Http\Controllers\Api\Stack\Admin\StackCheckController;

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

Route::fallback(function(){
    return response()->json([
        'message'=>'صفحه مورد نظر یافت نشد',
    ],404);
});


Route::group([
    'middleware' => 'api',

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);


    Route::get('/sendNotification' , [EmailVerifyController::class , 'sendNotification'])->middleware('auth');// send verification notification
    Route::post('/emailVerify' , [EmailVerifyController::class , 'emailVerify'])->middleware('auth');//verify user email

    //these routes can be used
    Route::post('/forgotPassword' , [ForgetPasswordController::class , 'sendEmail']);
    Route::post('/changePassword' , [ForgetPasswordController::class , 'changePassword']);

});


/*
|--------------------------------------------------------------------------
| admin panel routes
|--------------------------------------------------------------------------
*/
Route::group([

    'middleware'=>['auth' , 'verified'],
    'prefix'=>'admin',

], function($router){

    Route::group([

     'middleware'=>['auth.main_level:admin,author'],

    ] , function($router){

        Route::apiResource('posts' , PostController::class);

    });

    Route::group([

        'middleware'=>['auth.main_level:admin'],

       ] , function($router){

        Route::group([

            'prefix'=>'users',

        ], function($router){
            Route::get('/' , [UserController::class,'index']);
            Route::get('show/{id}' , [UserController::class , 'show']);
            Route::post('store' , [UserController::class , 'store']); // create a new user by admin
            Route::post('update-level/{id}' , [UserController::class , 'updateUserLevel']); // an admin changes a user level
            Route::delete('delete/{id}' , [UserController::class , 'destroy']);
        });


        Route::group([

            'prefix'=>'comments',

        ], function($router){
            Route::get('/' , [AdminCommentController::class,'index']);
            Route::get('show/{id}' , [AdminCommentController::class , 'show']);
            Route::post('approve/{id}' , [AdminCommentController::class , 'approve']);
            Route::post('reject/{id}' , [AdminCommentController::class , 'reject']);
            Route::delete('delete/{id}' , [AdminCommentController::class , 'destroy']);
        });

        Route::group([

            'prefix'=>'analyses',

        ], function($router){
            Route::get('/' , [AdminAnalysisController::class,'index']);
            Route::get('show/{id}' , [AdminAnalysisController::class , 'show']);
            Route::post('approve/{id}' , [AdminAnalysisController::class , 'approve']);
            Route::post('reject/{id}' , [AdminAnalysisController::class , 'reject']);
            Route::delete('delete/{id}' , [AdminAnalysisController::class , 'destroy']);
        });

        Route::post('userForm/approve/{id}' , [UserFormRequestController::class , 'approve']);
        Route::post('userForm/reject/{id}' , [UserFormRequestController::class , 'reject']);



        Route::apiResource('categories' , CategoryController::class);

        Route::apiResource('subcategories' , SubCategoryController::class);

        Route::apiResource('regulations' , RegulationController::class);

        Route::apiResource('products' , ProductController::class);

        Route::apiResource('plans' , PlanController::class);
       });
    });

/*
|--------------------------------------------------------------------------
| client side routes
|--------------------------------------------------------------------------
*/


Route::group([
    'prefix'=>'/',

],function($router){

        Route::group([
            'middleware'=>'auth',

        ] , function($route){

            Route::get('panel' , [PanelController::class , 'index']);// user panel


            Route::group([
                'middleware' => 'verified',
            ] , function($route){

                //analyses (create , update and delete)
                Route::post('store/analysis' , [AnalysisController::class , 'store']);
                Route::put('update/analysis/{id}' , [AnalysisController::class , 'update']);
                Route::delete('delete/analysis/{id}' , [AnalysisController::class , 'destroy']);
                Route::get('userAnalyses' , [AnalysisController::class  ,'index']);//show user Analyses


                //fundamental news (create , update and delete)
                Route::post('fundamental' , [FundamentalController::class , 'store']);
                Route::put('fundamental/{id}' , [FundamentalController::class , 'update']);
                Route::delete('fundamental/{id}' , [FundamentalController::class , 'destroy']);
                Route::get('fundamental/{id}' , [FundamentalController::class , 'show']);


                Route::post('addComment/analysis/{id}' , [AnalysisCommentController::class , 'commentStore']); // add a new comment for this post
                Route::post('addReply/analysisComment/{id}' , [AnalysisCommentController::class , 'replyStore']); // add new comment for a comment related to this post
                Route::put('editComment/analysisComment/{id}' , [AnalysisCommentController::class , 'commentUpdate']); // add a new comment for this post
                Route::put('editReply/analysisReply/{id}' , [AnalysisCommentController::class , 'replyUpdate']); // add new comment for a comment related to this post
                Route::delete('deleteAnalysisComment/{id}' , [AnalysisCommentController::class , 'destroyComment']);
                Route::delete('deleteAnalysisReply/{id}' , [AnalysisCommentController::class , 'destroyReply']);


                //form request from user to admins ( for being author or analyser or somthing else)
                Route::post('formRequest' , [RequestFormController::class, 'store']);
                Route::delete('formRequest/{id}' , [RequestFormController::class , 'destroy']);

            });

            //like and dislike posts
            Route::post('like/post/{id}', [PostLikeController::class , 'addLike']);
            Route::post('dislike/post/{id}', [PostLikeController::class , 'addDislike']);

            //like and dislike fundamental news
            Route::post('like/fundamental/{id}', [FundamentalLikeController::class , 'addLike']);
            Route::post('dislike/fundamental/{id}', [FundamentalLikeController::class , 'addDislike']);

            //like and dislike comments or replies
            Route::post('like/comment/{id}', [CommentLikeController::class , 'addLike']);
            Route::post('dislike/comment/{id}', [CommentLikeController::class , 'addDislike']);

            //like and dislike analyses
            Route::post('like/analysis/{id}', [AnalysisLikeController::class , 'addLike']);
            Route::post('dislike/analysis/{id}', [AnalysisLikeController::class , 'addDislike']);

        });

        Route::get('nav' , [NavBarController::class , 'index']);
        Route::get('navSubCategories/{id}' , [NavBarController::class , 'showSubCategories']);

        //home page routes
        Route::get('lastNews' , [HomePageController::class , 'lastNews']);
        Route::get('subNews/{id}' , [HomePageController::class , 'subNews']); //for example iran news and related posts
        Route::get('favAnalyses' , [HomePageController::class , 'favAnalyses']); // favorite analyses

        Route::get('/tags/{tag}' , [GetTagController::class , 'show']);//show posts related to this tag

        Route::get('/category/{id}' , [CategoryDetailsController::class , 'show']);//show category with subcategories and related posts

        Route::get('post/{id}' , [PostDetailsController::class , 'showPost']); // show post with comments and replies and tags

        Route::get('analysis/{id}' , [AnalysisController::class , 'show' ]);//get all analyses and show analysis

        Route::get('analyses' , [AnalysisController::class , 'lastAnalyses']);//get last Analyses

        Route::get('regulations' , [RegulationDetailsController::class , 'index']); // show all regulation countries
        Route::get('regulation/{id}' , [RegulationDetailsController::class , 'show']); // get regulstion details

        Route::get('products',[ProductDetailsController::class , 'index']);
        Route::get('product/{id}' , [ProductController::class , 'show']);


        //comments
        Route::post('addComment/post/{id}' , [CommentController::class , 'commentStore']); // add a new comment for this post
        Route::post('addReply/comment/{id}' , [CommentController::class , 'replyStore']); // add new comment for a comment related to this post
        Route::put('editComment/comment/{id}' , [CommentController::class , 'commentUpdate']); // add a new comment for this post
        Route::put('editReply/reply/{id}' , [CommentController::class , 'replyUpdate']); // add new comment for a comment related to this post
        Route::delete('deleteComment/{id}' , [CommentController::class , 'destroyComment']);
        Route::delete('deleteReply/{id}' , [CommentController::class , 'destroyReply']);
 });

 /*
|--------------------------------------------------------------------------
| stack routes
|--------------------------------------------------------------------------
*/


 Route::group([
    'prefix'=>'stack',

], function($router){

    Route::get('home' , [StackHomeController::class , 'index']);
    Route::get('question/{id}' , [StackQuestionController::class , 'show']);
    Route::get('questions' , [StackQuestionController::class , 'index']);
    //Route::get('questioncategory/{id}' ,[StackQuestionCategoryController::class , 'show']);

    Route::group([
        'middleware'=>['auth' , 'verified'],

    ],function($router){

        //questions (create , update and delete)
        Route::post('questions', [StackQuestionController::class, 'store']);
        Route::put('question/{id}', [StackQuestionController::class ,'update']);
        Route::delete('question/{id}', [StackQuestionController::class , 'destroy']);

        //comments
        Route::group([

            'middleware'=>['auth.stack_level:active,experienced,expert,specialist,professor,master,admin'],

        ],function($router){

        // add a new comment for the question
        Route::post('addComment/question/{id}', [StackCommentController::class , 'commentStore']);
        Route::put('editComment/comment/{id}', [StackCommentController::class , 'commentUpdate']);
        Route::delete('deleteComment/{id}', [StackCommentController::class , 'deleteComment']);

        // add a new comment for the answer
        Route::post('addComment/answer/{id}', [StackCommentController::class , 'commentStore']);
        Route::put('editComment/comment/{id}', [StackCommentController::class , 'commentUpdate']);
        Route::delete('deleteComment/{id}', [StackCommentController::class , 'deleteComment']);
        });

        //answers
        Route::post('addAnswer/question/{id}' , [StackAnswerController::class , 'store'])
        ->middleware('auth');
        Route::put('editAnswer/{id}' , [StackAnswerController::class , 'update'])
            ->middleware('auth');
        Route::get('answer/{id}' , [StackAnswerController::class , 'show'])
            ->middleware('auth');
        Route::delete('deleteAnswer/{id}' , [StackAnswerController::class , 'delete'])
            ->middleware('auth');

        //likes and disliked

        //like question
        Route::post('like/question/{id}', [StackLikeController::class , 'likeQuestion'])
        ->middleware('auth.stack_level:active,experienced,expert,specialist,professor,master,admin');
        // dislike question
        Route::post('dislike/question/{id}', [StackLikeController::class , 'dislikeQuestion'])
        ->middleware('auth.stack_level:expert,specialist,Professor,master,admin');

        //like answer
        Route::post('like/answer/{id}', [StackLikeController::class , 'likeAnswer'])
            ->middleware('auth.stack_level:active,experienced,expert,specialist,professor,master,admin');
        // dislike answer
        Route::post('dislike/answer/{id}', [StackLikeController::class , 'dislikeAnswer'])
            ->middleware('auth.stack_level:expert,specialist,Professor,master,admin');

        //like comments
        Route::post('like/comment/{id}', [StackLikeController::class , 'likeComment'])
        ->middleware('auth.stack_level:active,experienced,expert,specialist,professor,master,admin');
        // dislike comments
        Route::post('dislike/comment/{id}', [StackLikeController::class , 'dislikeComment'])
        ->middleware('auth.stack_level:experienced,expert,specialist,professor,master,admin');



        //approve the best comment as answer of question
        Route::post('approve/comment/{id}', [StackApproveAnswerController::class , 'approveComment']);

        //user panel in client side
        Route::get('panel' , [StackUserPanelController::class , 'index']);
        Route::put('panelUpdate' , [StackUserPanelController::class , 'update']);

    });
});


 /*
|--------------------------------------------------------------------------
| stack admin panel routes
|--------------------------------------------------------------------------
*/

Route::group([

    'middleware'=>['auth.main_level:admin' , 'verified' , 'auth.stack_level:admin'],
    'prefix'=>'stackAdmin',

], function($router){

    Route::get('/', [StackDashboardController::class  , 'index']);
    Route::delete('question/{id}' , [StackCheckController::class , 'destroyQuestion']);
    Route::delete('comment/{id}' ,[ StackCheckController::class , 'destroyComment']);
    Route::post('banOrUnban/{id}' , [StackCheckController::class , 'banUnbanUser']);//ban if user is unbanned or unban if user is banned


    Route::apiResource('categories' , StackCategoryController::class);
    Route::post('approve/question/{id}' , [StackApproveController::class , 'approveQuestion']);
});
