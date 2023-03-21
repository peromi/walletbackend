<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       

        $check_user_exist = Folder::where("user_id", $request->id)->first();

        if($check_user_exist == null){
            $folder = new Folder();

            if(isset($request->bvn)){
                $folder->bvn = Hash::make($request->bvn);
            }
            if(isset($request->nin)){
                $folder->nin = $request->nin;
            }
            if(isset($request->order_ref)){
                $folder->order_ref = $request->order_ref;
            }
            if(isset($request->account_ref)){
                $folder->account_ref = $request->account_ref;
            }
            
            if(isset($request->account_number)){
                $folder->account_name = $request->account_name;
                $folder->account_number = $request->account_number;
                $folder->bank_name = $request->bank_name;
            }
    
            $folder->user_id = $request->id;
    
            if($folder->save()){
                return json_encode(['data' => $folder, "message" => "Successful"]);
            }
        }else{
            $check_user_exist->account_number = $request->account_number;
            $check_user_exist->save();
            return json_encode(['data' => $check_user_exist, "message" => "Successful"]);
        }

       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function edit(Folder $folder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        

        if(isset($request->bvn)){
            $folder->bvn = $request->bvn;
        }
        if(isset($request->nin)){
            $folder->nin = $request->nin;
        }
        if(isset($request->order_ref)){
            $folder->order_ref = $request->order_ref;
        }
        if(isset($request->account_ref)){
            $folder->account_ref = $request->account_ref;
        }


        if($folder->save()){
            return json_encode(['data' => $folder, "message" => "Successful"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        //
    }
}
