<?php

use App\Http\Controllers\ActionHandleController;
use App\Models\AddressDetail;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\History;
use App\Models\Beneficiary;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Referrals;
use Illuminate\Support\Facades\Hash;
use Symfony\Contracts\Service\Attribute\Required;


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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/referrals/{id}', function($id){
    // Transaction History
        $referral = Referrals::where('user_id', $id)->get();
        return json_encode(['referral'=>$referral]);
    });

Route::get('/transactions/{id}', function($id){
// Transaction History
    $history = History::where('user_id', $id)->get();
    return json_encode(['history'=>$history]);
});

Route::get('/beneficiaries/{id}', function($id){
    // Beneficiaries
    $beneficiaries = Beneficiary::where('user_id', $id)->get();
    return json_encode(['beneficiaries'=>$beneficiaries]);
});

Route::post('/add-beneficiary', function(Request $request){
// Add beneficiaries


    $beneficiary = new Beneficiary();
    $beneficiary->user_id = $request->id;
    $beneficiary->name = $request->name;
    $beneficiary->account = $request->account;
    $beneficiary->bank = $request->bank;

    if($beneficiary->save()){
        $all = Beneficiary::where("user_id",$request->id)->get();
        return json_encode(['beneficiaries'=>$all]);
    }
});

Route::put('/edit-beneficiary/{id}', function(Request $request, $id){
    // Add beneficiaries


        $beneficiary = Beneficiary::find($id);
        $beneficiary->name = $request->name;
        $beneficiary->account = $request->account;
        $beneficiary->bank = $request->bank;

        if($beneficiary->save()){

            return json_encode(["beneficiary"=>$beneficiary, 'message'=>"Updated successfully."]);
        }
    });

    // Announcement
    Route::get('/announcement', function(){
        $announcement = Announcement::latest()->get()->take(1);

        if(count($announcement) > 0){
            return json_encode(['announcement' => $announcement]);
        }else{
            return json_encode(['message'=> "No announcement"]);
        }


    });

    // Upload documents by user
    Route::post('/add-document', [ActionHandleController::class,'addDocument']);

    // get documents
    Route::get('/get-document/{id}', function($id){
        $docs = Document::where('user_id', $id)->get();

        return json_encode(['docs' => $docs]);
    });

    // address information
    Route::post('/add-address', [ActionHandleController::class, 'addAddress']);


    Route::get("/get-address/{id}", function($id){
        $address = AddressDetail::where('user_id', $id)->first();
        return json_encode(['address' =>$address]);
    });
// Registration
// https://wallet.com/api-8908373784/new-register
Route::post('/new-register', [ActionHandleController::class, 'createNewUser']);

Route::post('/new-customer-paystack', function(Request $request){

    $customer = new Customer();
    $customer->user_id = $request->input('id');
    $customer->integration = $request->input('integration');
    $customer->customer_code = $request->input('customer_code');
    $customer->identified = $request->input('identified');
    $customer->identifications  = $request->input('identifications');

   if( $customer->save()){
    return json_encode(['message' =>  "Customer account added."]);
   }

});


Route::post("/new-login", [ActionHandleController::class, 'loginUser']);


Route::get("/test", function(){
    $test = array();
    return json_encode($test);
});
