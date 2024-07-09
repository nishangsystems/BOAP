<?php

namespace App\Http\Controllers;

use App\Models\CampusProgram;
use App\Models\Students;
use App\Models\User;
use Bmatovu\MtnMomo\Exceptions\CollectionRequestException;
use Bmatovu\MtnMomo\Exceptions\MtnMomoRequestException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function set_local(Request $request, $lang)
    {
        # code...
        // return $lang;
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('appLocale', $lang);
            App::setLocale($lang);
        }
        return back();
    }

    

    public function make_payment(Request $request){
        try {

            // return random_int(111111011010, 999999999999);
            $collection = new Collection();
            
            // return response($request->all(), 400);
            $momoTransactionId = $request->collect();
            // dd($momoTransactionId);
            //save transaction
           $transaction = new \App\Models\Transaction();
           $transaction->payment_method = 'Mtn Mobile Money';
           $transaction->payment_purpose = $request->payment_purpose ?? '';
           $transaction->status = 'pending'; //pending,failed,completed
           $transaction->year_id = $request->year_id ?? \App\Helpers\Helpers::instance()->getCurrentAccademicYear();
           $transaction->amount = intval($request->amount);
           $transaction->reference = $request->reference ?? time().random_int(100000, 999999);
           $transaction->transaction_id = $momoTransactionId;
           $transaction->payment_id = $request->payment_id;
           $transaction->student_id = $request->student_id;
           $transaction->semester_id = $request->semester_id ?? null;
        //    $transaction->callback_url = $request->callback_url;
           $transaction->save();
        //    return $momoTransactionId;
           if($momoTransactionId == false || $momoTransactionId == null){
               return response('Operation failed. Unable to trigger payment. Make sure you are connected and try again', 500);
            }
            else{
                $data['transaction_Id'] = $momoTransactionId;
                return response()->json($data);
            }
        } catch (MtnMomoRequestException $e) {
            // do {
            //     printf("\n\r%s:%d %s (%d) [%s]\n\r",
            //         $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
            // } while ($e = $e->getPrevious());
            return response($e->getCode().' : '.$e->getMessage(), 500);
        } catch (CollectionRequestException $e) {
            // do {
            //     printf("\n\r%s:%d %s (%d) [%s]\n\r",
            //         $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
            // } while ($e = $e->getPrevious());
            return response($e->getCode().' : '.$e->getMessage(), 500);
        }
    }

    
}
