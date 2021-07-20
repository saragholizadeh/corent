<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Like;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Analysis;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAnalysisRequest;
use App\Http\Resources\AnalysisCollection;
use App\Http\Resources\Analysis as AnalysisResources;


class AnalysisController extends Controller
{
    /**
     * Display a listing of the user analyses (in user panel).
     *
     */
    public function index()
    {
        $user = JWTAuth::user()->id;

        $analysis = new AnalysisCollection(Analysis::where('user_id' , $user)->orderBy('created_at',  'desc')->get());

        return response()->json([
            'analyses'=>$analysis
        ] , 200);
    }


    /**
     * display last analyses (all users can see)
     *
     */
    public function lastAnalyses(){
        $analyses = new AnalysisCollection (Analysis::orderBy('created_at' , 'desc')->get());

        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );

        $responsecode = 200;

        return response()->json([
            'last_analyses' => $analyses,

        ]  , $responsecode, $header, JSON_UNESCAPED_UNICODE);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnalysisRequest $request){

        $validatedData = $request->all();

        $user_id = JWTAuth::user()->id;

        $validatedData['user_id'] = $user_id;


        if ($request->hasFile('image')){

            $files = $request->file('image');

            foreach($files as $file){

                $imageName = time().rand(1,1000).".".$file->extension();

                $analysisTitle = $request->title;

                $imagePath = public_path().'/images/analyses/کاربر.'.JWTAuth::user()->id.$analysisTitle;

                $file->move($imagePath ,$imageName );

                $image = new Image();
                $image->image = $imageName;
                $image->path = $imagePath;

                $images[] = $image;

            }
        }
            $tags = explode("," , $request->tags);

            $analysis =new AnalysisResources( Analysis::create($validatedData));
            $analysis->images()->saveMany($images);
            $analysis->tag($tags);

            return response()->json([
                "success" => true,
                "message" => "با موفقیت ثبت گردید",
                "data" => $analysis
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            $analysis = new AnalysisResources(Analysis::find($id));

            if(is_null($analysis)){
                return response()->json('تحلیل  مورد نظر یافت نشد' , 404);
            }

            //record new view
            $analysisView=Analysis::find($id);
            views($analysisView)->record();
            $views = views($analysisView)->count();

             //show comments of analyses
            $comments = Comment::where('commentable_type' , 'App\Models\Analysis')->first();

            if ($comments==null) {
                $comments = 'there is no comment for this analysis yet';
            }else{
                $comments = Comment::where('commentable_type', 'App\Models\Analysis')->with('replies.replies')->get();
            }
            return response()->json([
               'analysis' => $analysis,
               'analysis_views' => $views,
               'comments'=>$comments,
                ] , 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAnalysisRequest $request, $id)
    {
        $analysis_failed = Analysis::find($id);
        if (is_null($analysis_failed)) {
            return response()->json(' پست  مورد نظر یافت نشد', 404);
        }

        $validatedData = $request->all();
        $user_id = JWTAuth::user()->id;
        $validatedData['user_id'] = $user_id;

        $tags = explode(",", $request->tags);

        if ($request->hasfile('image')) {
            $analysisTitle = $request->title; //analysis title for folder name and the images inside it

            //delete last Images from database for updating images
            Image::where('imageable_type' , 'App\Models\Analysis')->where('imageable_id' , $id)->delete();
            //delete last    images folder
            //File::delete(public_path('/images/ana$analysiss/'.$analysisTitle));
            $files = $request->file('image');

            foreach ($files as $file) {

                $imageName = time().rand(1,1000).".".$file->extension();
                $imagePath = public_path().'/images/analyses/کاربر.'.JWTAuth::user()->id.$analysisTitle;
                $file->move($imagePath, $imageName);

                $image = new Image;
                $image->image = $imageName;
                $image->path = $imagePath;

                $images[] = $image; // make an array of uploaded images
            }
        }

        $analysis = Analysis::find($id);

        $analysis->title = $validatedData['title'];
        $analysis->exchange = $validatedData['exchange'];
        $analysis->summary = $validatedData['summary'];
        $analysis->description = $validatedData['description'];
        $analysis->pair = $validatedData['pair'];
        $analysis->timeframe = $validatedData['timeframe'];

        $analysis->save();
        $analysis->images()->saveMany($images);
        $analysis->tag($tags);


        return response()->json([
        "success" => true,
        "message" => "با موفقیت ویرایش گردید",
        "data" => $analysis
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $analysis = Analysis::findOrfail($id);
        if(is_null($analysis)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }
         $analysis->delete();

         return response()->json([
            "success" => true,
            "message" => "دسته بندی مورد نظر با موفقیت حذف شد",
            ]);
    }
}
