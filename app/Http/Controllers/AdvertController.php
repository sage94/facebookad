<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advert;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

class AdvertController extends Controller
{
public function __construct() {

        $this->middleware('auth');
        $this->middleware('adminguard');
}
     
    
public function addads()
    {

        $params = [
            'title' => 'Add an Ad',        
        ];

        return view('admin.addads')->with($params);
    }

   

    function addads2(Request $request)
    { 

    $this->validate($request,[
        'advert' =>'bail|required|mimes:jpeg,png',]);

        $file = $request->advert;
        $description = $request->description;
        $website=$request->website;
        $title=$request->title;
 	    $name =\Ramsey\Uuid\Uuid::uuid4();
        $name = $name->toString();
   
        if($file->getSize()<1000000){
            

        
               $fileName =$name.'.'.$file->getClientOriginalExtension();

              // $name= $file->getClientOriginalName();
               
              

            if( $file->storeAs('public',$fileName)){

                $imageurl= Storage::url($fileName);
                
                        
                Advert::create([
                
                'description' => $description,
                'website'=>$website,
                'title'=>$title,
                'address'=>$imageurl ]);
                                
                $params = [
                    'title' => 'Add an Ad',
                    'status' => 'Ad added successfully' ];
                                    
                return redirect('/ads/add')->with($params);

                }
                else{
                        return redirect('/ads/add')->withErrors('Error uploading file ,try again later..');
                    
                }

      }
        
      else{
		        return redirect('/ads/add')->withErrors('You must upload a file size less than 1MB ');

		 }
    

    }

    function publishad(Request $request){

        $id = $request->advertid;

        $adverts = DB::insert('update adverts set state = 1 where id = ?',[$id]);

                     $params = [
                    'title' => 'Add an Ad',
                    'status' => 'Ad published successfully'
                    
                     ];

        // $advert_link =  DB::select('select * from adverts where id = ? ',[$id]);     
        // $adlink= $advert_link[0]->address;
        // $publishers = DB::select('select * from publishers  ');

        // foreach($publishers as $publisher)
        //     {
        //         $pub_id = $publisher->id;
        //         $publish = DB::insert('insert into evidences(publisher_id,ad_id,adlink) values(?,?,? )',[$pub_id,$id,$adlink]);

        //     }

        return redirect('/admin')->with($params);
            
    }


    function unpublishad(Request $request){

        $id = $request->advertid;

        $adverts = DB::update('update adverts set state = 0 where id = ?',[$id]);

                $params = [
                    'title' => 'FaceBook Ads',
                    'status' => 'Ad has been removed from those published successfully'     
                        ];
                $publishers = DB::delete('delete from ad_pubs where ad_id = ? ',[$id]);


                return redirect('/ads/publish')->with($params);

            
    }

    function publishedads(){

    	 $params = [
            'title' => 'Published Ads',
            
        ];
        
        $adverts = DB::select('select * from adverts where state = 1');
 
        return view('admin.publishedads',compact('adverts'))->with($params);

    }


    function deleteads(Request $request){

        $id = $request->advertid;

        $delete=advert::findOrFail($id);

    
        $emeka=substr($delete->address,9);
        //dd($emeka);
        Storage::delete($emeka);
        Storage::delete($delete->address);
        $adverts = DB::insert('Delete from adverts where id = ?',[$id]);



        $params = [
                    'title' => 'Published Ads',
                    'status' => 'Ad deleted successfully'
            
        ];

         return redirect('/admin')->with($params);
    
    }

   
    
}


     

