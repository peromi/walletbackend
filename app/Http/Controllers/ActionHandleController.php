<?php

namespace App\Http\Controllers;

use App\Models\AddressDetail;
use App\Models\Document;
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

    public function addDocument(Request $request){
        $this->validate($request,[
            'type' => 'required', 
            'data' => 'image|mimes:jpg,png, jpeg|max:3048',
            'back' => 'image|mimes:jpg,png, jpeg|max:3048'
        ]);


        $docs = new Document();
        $docs->user_id = $request->input('id');
        $docs->type = $request->input('type');
       
       
       
        

        if($request->hasFile("data")){

            // Address 
            $fileData = $request->file("data")->getClientOriginalName();
            $fileName = pathinfo($fileData, PATHINFO_FILENAME);

            $ext = pathinfo($fileData, PATHINFO_EXTENSION);
            $fileToSave = md5($fileName.time()).".".$ext;

            $request->file('data')->storeAs("/public/document/", $fileToSave);

           
            

            $docs->data = $fileToSave;
            
        }
        if($request->hasFile("back")){

            // Address 
            $fileData = $request->file("back")->getClientOriginalName();
            $fileName = pathinfo($fileData, PATHINFO_FILENAME);

            $ext = pathinfo($fileData, PATHINFO_EXTENSION);
            $fileToSave = md5($fileName.time()).".".$ext;

            $request->file('back')->storeAs("/public/document/", $fileToSave);

           
            

            $docs->back = $fileToSave;
            
        }
        


        if($docs->save()){
            return json_encode(['message'=>'Successful.']);
        }else{
            return json_encode(['message'=>'Something went wrong']);
        }





    }

    public function addAddress(Request $request){
        $this->validate($request,[
            'state' => 'required',
            'lga' => 'required',
            'address' => 'required',
            'street' => 'required|image|mimes:jpg,png, jpeg|max:3048',
            'house' => 'required|image|mimes:jpg,png, jpeg|max:3048'
        ]);


         
       
        $address = new AddressDetail();
        $address->user_id = $request->input('id');
        $address->state = $request->input('state');
        $address->lga = $request->input('lga');
        $address->address = $request->input('address');
        $address->landmark = $request->input('landmark');

        if($request->hasFile("house") && $request->hasFile("street")){

            // Address 
            $fileAddress = $request->file("street")->getClientOriginalName();
            $fileAddressName = pathinfo($fileAddress, PATHINFO_FILENAME);

            $ext = pathinfo($fileAddress, PATHINFO_EXTENSION);
            $fileAddressToSave = md5($fileAddressName.time()).".".$ext;

            $request->file('street')->storeAs("/public/street/", $fileAddressToSave);

            $address->street = $fileAddressToSave;
            


            // house
                        $fileHouse = $request->file("house")->getClientOriginalName();
                        $fileHouseName = pathinfo($fileHouse, PATHINFO_FILENAME);
            
                        $ext = pathinfo($fileHouse, PATHINFO_EXTENSION);
                        $fileHouseToSave = md5($fileHouseName.time()).".".$ext;
            
                        $request->file('house')->storeAs("/public/house/", $fileHouseToSave);
            
                $address->house = $fileHouseToSave;
        }
        


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
