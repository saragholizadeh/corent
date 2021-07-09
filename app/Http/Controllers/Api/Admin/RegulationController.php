<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Image;
use App\Models\Regulation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreRegulationRequest;

class RegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort' , 'created_at');

        $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc' : 'asc' ;

        $sortColumn = ltrim($sortColumn , '-');

        $regulation = Regulation::orderBy($sortColumn , $sortDirection)->paginate(10);

        return response()->json($regulation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegulationRequest $request )
    {
        $validatedData = $request->all();

        $image = new Image;
        $getImage = $request->image;
        $imageName = time().'.'.$getImage->extension();
        $regulationCountry = $request->country;
        $imagePath = public_path(). '/images/regulations/'.$regulationCountry;

        $image->path = $imagePath;
        $image->image = $imageName;

        $getImage->move($imagePath, $imageName);

        $regulation = Regulation::create($validatedData);

        $regulation->image()->save($image);

        return response()->json([
            "success" => true,
            "message" => "با موفقیت ثبت گردید",
            "data" => $regulation
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $regulation = Regulation::find($id);
        if(is_null($regulation)){
            return response()->json('قانون گذاری  مورد نظر یافت نشد' , 404);
        }
        return response()->json($regulation , 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRegulationRequest $request, $id )
    {
        $reultion_faild = Regulation::find($id);
        if(is_null($reultion_faild)){
            return response()->json(' پست  مورد نظر یافت نشد' , 404);
        }

        $validatedData = $request->all();

        $regulationCountry = $request->country; //regulation country for folder name and the image inside it

        //delete last Images from database for updating images
        Image::where('imageable_type' , 'App\Models\Regulation')->where('imageable_id' , $id)->delete();
        //delete last images images folder
        //File::delete(public_path('/images/regulations'));

        $image = new Image;
        $getImage = $request->image;
        $imageName = time().'.'.$getImage->extension();
        $imagePath = public_path(). '/images/regulations/'.$regulationCountry;

        $image->path = $imagePath;
        $image->image = $imageName;

        $getImage->move($imagePath, $imageName);


        $regulation = Regulation::find($id);
        $regulation->country = $validatedData['country'];
        $regulation->description = $validatedData['description'];
        $regulation->short_description = $validatedData['short_description'];
        $regulation->area = $validatedData['area'];
        $regulation->internet_penetration = $validatedData['internet_penetration'];
        $regulation->national_currency = $validatedData['national_currency'];
        $regulation->goverment = $validatedData['goverment'];
        $regulation->president = $validatedData['president'];
        $regulation->capital = $validatedData['capital'];
        $regulation->language = $validatedData['language'];
        $regulation->economic_growth = $validatedData['economic_growth'];
        $regulation->dgtl_curr_lgs = $validatedData['dgtl_curr_lgs'];
        $regulation->dgtl_curr_tax = $validatedData['dgtl_curr_tax'];
        $regulation->dgtl_curr_pymt = $validatedData['dgtl_curr_pymt'];
        $regulation->dgtl_curr_ntiol = $validatedData['dgtl_curr_ntiol'];
        $regulation->ICO = $validatedData['ICO'];
        $regulation->crpto_antimon_rules = $validatedData['crpto_antimon_rules'];

        $regulation->update();
        $regulation->image()->save($image);


        return response()->json([
        "success" => true,
        "message" => "با موفقیت ویرایش گردید",
        "data" => $regulation
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
        $regulation = Regulation::findOrfail($id);
        if(is_null($regulation)){
            return response()->json('قانون گذاری  مورد نظر یافت نشد' , 404);
        }
         $regulation->delete();

         return response()->json([
            "success" => true,
            "message" => "قانون گذاری  مورد نظر با موفقیت حذف شد",
            "data" => $regulation
            ]);
    }
}
