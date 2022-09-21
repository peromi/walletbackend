<?php

namespace App\Http\Controllers;

use App\Models\AddressDetail;
use App\Models\User;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ActionHandleController extends Controller
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

    public function addAddress(Request $request){
        $house = $request->house;
        $street = $request->street;
        $address = new AddressDetail();
        $address->user_id = $request->input('id');
        $address->state = $request->input('state');
        $address->lga = $request->input('lga');
        $address->address = $request->input('address');
        $address->landmark = $request->input('landmark');
        $address->house = $house;
        $address->street = $street;


            if($address->save()){
                return json_encode(['message'=>'Address verified.']);
            }else{
                return json_encode(['message'=>'Something went wrong']);
            }





    }
    public function loginUser(Request $request){
        $this->validate($request,[
            'mobile' => 'required|numeric|min:11',
            'password' => 'required',
        ]);

        $user = User::where("mobile", $request->mobile)->first();
        if(!$user || !password_verify($request->password, $user->password)){
            return json_encode(['message' => 'Incorrect credentials, try again.']);
        }
        return json_encode(['user'=>$user]);
    }

    public function createNewUser(Request $request){
    $this->validate($request,[
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'mobile' => 'required|numeric|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);



        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->mobile =$request->mobile;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);


        if($user->save()){
            return json_encode(['user'=>$user]);
        }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
