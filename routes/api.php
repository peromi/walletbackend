<?php

use App\Http\Controllers\ActionHandleController;
use App\Http\Controllers\FolderController;
use App\Models\AddressDetail;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\History;
use App\Models\Beneficiary;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Folder;
use App\Models\Referrals;
use Illuminate\Support\Facades\Hash;
use Symfony\Contracts\Service\Attribute\Required;

use function PHPUnit\Framework\throwException;

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

Route::get("/getuser/{username}", function ($username){
    $user = User::where('username', $username)->first();
    return json_encode(['user' => $user]);
}); 
Route::get('/get-all-users', function(){
    $users = User::all();
    return json_encode(['users' => $users]);
});
Route::get('/referrals/{id}', function($id){
    // Transaction History
        $referral = Referrals::where('user_id', $id)->get();
        return json_encode(['referral'=>$referral]);
});

Route::post("/user-to-user",function(Request $request){

    $receiver = User::where('username', $request->username )->first();
    $sender = User::where('id', $request->id)->first();

    $folder = Folder::where('user_id', $receiver->id)->first();

   
    
    if($receiver == null){
        return json_encode(['message' => 'Invalid username or user not found. '. $request->username ]);
    }elseif($receiver->username == $sender->username){
        return json_encode(["message"=>"You can't send money to yourself."]);
       
    } elseif($folder == null){
        return json_encode(['message' => 'This username has not verified BVN.']);
    }else{
        $credit = Folder::where('user_id', $receiver->id)->first();
        $credit->account_balance = floatval($credit->account_balance) + floatval($request->amount);

        if($credit->save()){

            // Debit sender
            $debit = Folder::where('user_id', $sender->id)->first();
            $debit->account_balance = floatval($debit->account_balance)-floatval($request->amount);

            $debit->save();

            // Receiver history
            $history = new History();
            $history->user_id = $receiver->id;
            $history->name = strtoupper(substr($sender->firstname,0,1)).strtoupper(substr($sender->firstname,1)). " " . strtoupper(substr($sender->lastname,0,1)).strtoupper(substr($sender->lastname,1)) . " sent you ".$request->amount."NGN.";
            $history->amount = $request->amount;
            $history->action = "credit";
            $history->status = "successful";
            $history->ref = $request->ref;
            $history->save();


            // sender history
            $historySender = new History();
            $historySender->user_id = $sender->id;
            $historySender->name ="You sent ". strtoupper(substr($receiver->firstname,0,1)).strtoupper(substr($receiver->firstname,1)) . " " . strtoupper(substr($receiver->lastname,0,1)).strtoupper(substr($receiver->lastname,1)) ." ".$request->amount."NGN.";
            $historySender->amount = $request->amount;
            $historySender->action = "debit";
            $historySender->status = "successful";
            $historySender->ref = $request->ref;
            $historySender->save();

            return json_encode(["folder"=>$debit,'message' => "Transfer successful"]);
        }else{
            return json_encode(['message' => "Transfer unsuccessful"]);
        }

    }
});   
Route::post("/add-transaction", function (Request $request){
    // $sender = User::where('id', $request->id)->first();

    $debit = Folder::where('user_id', $request->id)->first();
    $debit->account_balance = floatval($debit->account_balance)-floatval($request->amount);

    $debit->save();

    $historySender = new History();
    $historySender->user_id = $request->id;
    $historySender->name = $request->description;
    $historySender->amount = $request->amount;
    $historySender->action = $request->action;
    $historySender->status = "successful";
    $historySender->ref = $request->ref;
    $historySender->save();
    return json_encode(['message'=>"Transaction saved successfully"]);
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


    $check_already_exists = Beneficiary::where('account', $request->account)->first();
    if($check_already_exists == null){
        $beneficiary = new Beneficiary();
        $beneficiary->user_id = $request->id;
        $beneficiary->name = $request->name;
        $beneficiary->account = $request->account;
        $beneficiary->bank = $request->bank;
        $beneficiary->bank_code = $request->bank_code;
    
        if($beneficiary->save()){
            $all = Beneficiary::where("user_id",$request->id)->get();
            return json_encode(['beneficiaries'=>$all]);
        }
    }else{
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
            $all = Beneficiary::where("user_id",$beneficiary->user_id)->get();
            return json_encode(["beneficiary"=>$all, 'message'=>"Updated successfully."]);
        }
    });

    Route::post('/delete-beneficiary', function(Request $request){
        $beneficiary = Beneficiary::find($request->id);
        if($beneficiary != null){
            
            $all = Beneficiary::where("user_id",$request->user_id)->get();
            $beneficiary->delete();
            return json_encode(["beneficiary"=>$all, 'message'=>"Deleted successfully."]);
        }else{
            $all = Beneficiary::where("user_id",$request->user_id)->get();
            
            return json_encode(["beneficiary"=>$all ]);
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
        $docs = Document::where('user_id', $id)->first();

        return json_encode(['docs' => $docs]);
    });

    // address information
    Route::post('/add-address', [ActionHandleController::class, 'addAddress']);


    Route::get("/get-address/{id}", function($id){
        $address = AddressDetail::where('user_id', $id)->first();
        return json_encode(['address' =>$address]);
    });


    // Add bvn nin orderref accountref
    Route::get('/folder/{id}', function($id){
        $data = Folder::where("user_id", $id)->first();

        return json_encode(['data' => $data]);
    });
    Route::post('/folder', [FolderController::class, 'store']);
    Route::post('/update-folder/{id}', function (Request $request, $id){
        $folder = Folder::where("user_id", $id)->first();
        if(isset($request->bvn)){
            $folder->bvn = $request->bvn;
        }
        if(isset($request->nin)){
            $folder->nin = $request->nin;
        }
        if(isset($request->order_ref)){
            $folder->order_ref = $request->order_ref;
        }
        if(isset($request->tx_ref)){
            $folder->tx_ref = $request->tx_ref;
        }
        if(isset($request->account_ref)){
            $folder->account_ref = $request->account_ref;
        }
        if(isset($request->virtual_card_id)){
            $folder->virtual_card_id = $request->virtual_card_id;
        }
        if(isset($request->bank_code)){
            $folder->bank_code = $request->bank_code;
        }
        if(isset($request->account_balance)){
            $folder->account_balance = floatval($folder->account_balance)  + floatval($request->account_balance);
        }

 


        if($folder->save()){
            return json_encode(['data' => $folder, "message" => "Successful"]);
        }
    });
// Registration
// https://wallet.com/api-8908373784/new-register
Route::post('/new-register', [ActionHandleController::class, 'createNewUser']);
Route::post('/new-referral-registration/{username}', [ActionHandleController::class, 'createNewUserThroughReferral']);

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


Route::get('/user-data/{id}', function($id){
    $data = Folder::where("user_id", $id)->first();
    $address = AddressDetail::where('user_id', $id)->first();
    $docs = Document::where('user_id', $id)->first();
    $beneficiary = Beneficiary::where('user_id',$id)->get(); 
   return json_encode(['data'=>$data, 'beneficiary'=>$beneficiary, 'address'=>$address, 'docs'=>$docs]);  
});

Route::post("/new-login", [ActionHandleController::class, 'loginUser']);


Route::get("/test", function(){
    $test = array();
    return json_encode($test);
});
