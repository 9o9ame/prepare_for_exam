<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Vendor;
use App\Models\vendor_main_categories;
use App\Models\Vendor_category;
use App\Models\Vendor_Offer;
use App\Models\User;
use App\Models\Vendor_Offer_Product;
use App\Models\Vendor_Shop_Visit;
use App\Models\Vendors_Subsciber;
use App\Models\vendor_rating;
use App\Models\Vendor_cover;
use App\Models\Vendor_Product;
use App\Models\UserOrders;
use App\Models\Category;
use App\Models\Notification;
use App\Models\vendor_products_addon;
use App\Models\vendor_product_adons_map;
use App\Models\vendor_products_variant;
use App\Models\vendor_txn_log;
use App\Jobs\ProcessPush;
use App\Models\vendor_table;
use App\Models\vendor_products_addons;
use App\Models\vendor_payout_account;
use App\Models\Feed_Save;
use App\Models\user_order_product;
use App\Models\Feed;
use App\Models\user_cart;
use App\Models\vendor_timing;
use App\Models\user_orders_txn_log;
use App\Models\vendor_pickup_point;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Storage;
use Illuminate\Support\Str;

use App\Events\KotstatusChannel;
use App\Events\OrderstatusChannel;
class VendorController extends Controller
{
    public function fetch_table_vendors(Request $request)
    {
        $vendor_id = Auth::user()->id;

        $table = vendor_table::where('vendor_id', $vendor_id)->get();

        if (count($table) > 0) {
            $response['status'] = true;
            $response['data'] = $table;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No Tables found';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function fetch_vendor_txn(Request $request)
    {
        $vendor_id = Auth::user()->id;

        $table = vendor_txn_log::where('vendor_id', $vendor_id)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        if (count($table) > 0) {
            $response['status'] = true;
            $response['data'] = $table;
            $response['wallet'] = Auth::user()->wallet;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No Tables found';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function fetch_bank_account_vendor(Request $request)
    {
        $vendor_id = Auth::user()->id;
        $order = vendor_payout_account::where('vendor_id', $vendor_id)->get();

        return json_encode($order);
    }
    //update bankaccount no of vendor

    public function update_bank_account_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_account_no' => 'required',
            'confirm_bank_account_no' => 'required',
            'bank_ifsc' => 'required',
            'beneficiary_name' => 'required',
        ]);
        //return Auth::user()->id;

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        if ($request->bank_account_no != $request->confirm_bank_account_no) {
            $response['status'] = false;
            $response['msg'] = 'Confirm bankaccount mismatch';
            return json_encode($response);
        }

        $account = vendor_payout_account::where('vendor_id', Auth::user()->id)->get();

        if (count($account) > 0) {
            $response['status'] = false;
            $response['msg'] = 'not Updated, please all our support team for this.';
            return json_encode($response);
        }

        //call razorpay payout

        $ch = curl_init();
        $Params = [
            'name' => Auth::user()->shop_name,
            'email' => Auth::user()->email,
            'contact' => Auth::user()->contact,
            'type' => 'vendor',
        ];

        $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);

        curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/contacts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_USERPWD, env('RAZORPAY_KEY') . ':' . env('RAZORPAY_SECRET'));

        $headers = [];
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch));

        if (isset($result->error)) {
            $response['status'] = false;
            $response['msg'] = $result->error->description;
            return json_encode($response);
        }
        //print_r($result);
        $payout_account_id = $result->id;

        //create fund account for this
        $ch = curl_init();

        $Params = [
            'account_type' => 'bank_account',
            'contact_id' => $payout_account_id,
            'bank_account' => ['name' => $request->beneficiary_name, 'ifsc' => $request->bank_ifsc, 'account_number' => $request->bank_account_no],
        ];

        $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);

        curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/fund_accounts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_USERPWD, env('RAZORPAY_KEY') . ':' . env('RAZORPAY_SECRET'));

        $headers = [];
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch));

        if (isset($result->error)) {
            $response['status'] = false;
            $response['msg'] = $result->error->description;
            return json_encode($response);
        }
        $fund_id = $result->id;

        $bank = new vendor_payout_account();

        $bank->bank_account_no = $request->bank_account_no;
        $bank->bank_ifsc = $request->bank_ifsc;
        $bank->beneficiary_name = $request->beneficiary_name;
        $bank->vendor_id = Auth::user()->id;
        $bank->payout_account_id = $payout_account_id;
        $bank->payout_account_status = 'active';

        $bank->payout_fund_id = $fund_id;

        if ($bank->save()) {
            $response['status'] = true;
            $response['data'] = 'successfully updated';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Could not be updated';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function add_new_table_vendor(Request $request)
    {
        $vendor_id = Auth::user()->id;
        $table = vendor_table::where('vendor_id', $vendor_id)->get();

        $final = sizeof($table) + 1;
        //return $final;
        $table_name = 'Table ' . $final;
        $data = new vendor_table();

        $uniq = Str::uuid()->toString();
        $data->table_uu_id = $uniq;
        $data->vendor_id = $vendor_id;

        $data->qr_link = $link = env('APP_API_URL') . '/qr-img/' . $uniq;

        $data->table_name = $table_name;
        $data->table_status = 'active';

        if ($data->save()) {
            $table_id = $data->id;

            $link = env('APP_URL') . '/' . $uniq . '/dineinlisting';

            //$globalclass = new GlobalController();

            //Remove Previous Image
            //$res=$globalclass->genrate_qr($link,Auth::user()->name,$table_name);

            $response['status'] = true;
            $response['data'] = $table;
            $response['msg'] = 'Table Added!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Table could not be Added!';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function update_other_charges_vendor(Request $request)
    {
        $vendor_id = Auth::user()->id;
        $vendor = Vendor::find($vendor_id);

        $vendor->gstin = $request->gstin;
        $vendor->gst_percentage = $request->gst_percentage;
        $vendor->service_charge = $request->service_charge;
        $vendor->gst_type = $request->gst_type;
        if ($vendor->save()) {
            $response['status'] = true;
            $response['msg'] = 'Charges Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'charges could not be updated';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function delete_table_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required',
        ]);
        //return Auth::user()->id;

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        $data = vendor_table::where('table_uu_id', $request->table_id)
            ->where('table_status', 'active')
            ->delete();

        if ($data) {
            $response['status'] = true;
            $response['msg'] = 'Table deleted!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Table could not be daleted!';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function add_product_addon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addon_name' => 'required',
            'addon_price' => 'required',
        ]);
        //return Auth::user()->id;

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        $addon = new vendor_products_addon();

        $addon->addon_name = $request->addon_name;
        $addon->addon_price = $request->addon_price;
        $addon->vendor_id = $vendor_id;
        if ($addon->save()) {
            $response['status'] = true;
            $response['msg'] = 'Successfully Added!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'could not be added';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function fetch_product_addon(Request $request)
    {
        $vendor_id = Auth::user()->id;

        $addon = vendor_products_addon::where('vendor_id', $vendor_id)->get();

        if (count($addon) > 0) {
            $response['status'] = true;
            $response['data'] = $addon;
        } else {
            $response['status'] = false;
            $response['msg'] = 'no record found';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_vendor_data(Request $request)
    {
        $vendor_id = Auth::user()->id;

        if (isset($request->range)) {
            $range = $request->range;
            if ($request->range == 'custom') {
                $from = date('Y-m-d 00:00:00', strtotime($request->from));
                $to = date('Y-m-d 23:59:59', strtotime($request->to));
            } else {
                $date_range = AppHelper::get_date_range($request->range);
                $from = $date_range['from'];
                $to = $date_range['to'];
            }
        } else {
            $from = date('Y-m-d 00:00:00');
            $to = date('Y-m-d 23:59:59');
        }

        $response['customer'] = UserOrders::where('vendor_id', $vendor_id)
            ->whereBetween('created_at', [$from, $to])
            ->distinct()
            ->count('user_id');

        $response['shop_visit'] = Vendor_Shop_Visit::where('vendor_id', $vendor_id)
            ->whereBetween('created_at', [$from, $to])
            ->where('user_activity', 'shop_visit')
            ->count();
        $response['contact'] = Vendor_Shop_Visit::where('vendor_id', $vendor_id)
            ->whereBetween('created_at', [$from, $to])
            ->where('user_activity', 'contact')
            ->count();

        $response['orders'] = UserOrders::where('vendor_id', $vendor_id)
            ->whereNotIn('order_status', ['failed', 'cancelled'])
            ->whereBetween('created_at', [$from, $to])
            ->count('id');

        $response['link'] = env('APP_URL') . '/' . $vendor_id . '/home';
        //        $response['followers']=Vendors_Subsciber::where('vendor_id',$vendor_id)->count();
        $response['total_earnning'] = user_orders_txn_log::whereIn('order_id', function ($q) use ($vendor_id) {
            $q->from('user_orders')
                ->selectRaw('id')
                ->where('vendor_id', $vendor_id);
        })
            ->whereBetween('created_at', [$from, $to])
            ->where('txn_status', 'success')
            ->sum('txn_amount');

        $response['cashsale'] = user_orders_txn_log::whereIn('order_id', function ($q) use ($vendor_id) {
            $q->from('user_orders')
                ->selectRaw('id')
                ->where('vendor_id', $vendor_id);
        })
            ->where('txn_method', 'Cash')
            ->where('txn_status', 'success')
            ->whereBetween('created_at', [$from, $to])
            ->sum('txn_amount');

        $response['weazypay'] = user_orders_txn_log::whereIn('order_id', function ($q) use ($vendor_id) {
            $q->from('user_orders')
                ->selectRaw('id')
                ->where('vendor_id', $vendor_id);
        })
            ->where('txn_channel', 'online')
            ->where('txn_status', 'success')
            ->whereBetween('created_at', [$from, $to])
            ->sum('txn_amount');

        $response['online'] = user_orders_txn_log::whereIn('order_id', function ($q) use ($vendor_id) {
            $q->from('user_orders')
                ->selectRaw('id')
                ->where('vendor_id', $vendor_id);
        })
            ->where('txn_channel', 'offline')
            ->where('txn_method', '!=', 'Cash')
            ->whereBetween('created_at', [$from, $to])
            ->where('txn_status', 'success')
            ->sum('txn_amount');

        $res['status'] = true;
        $res['data'] = $response;
        return json_encode($res);
    }

    public function fetch_kot_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        //return Auth::user()->id;

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        if ($request->status == 'all') {
            $data = UserOrders::with('user')
                ->with('table')
                ->where('vendor_id', $vendor_id)
                ->whereIn('order_status', ['in_process', 'confirmed', 'ongoing'])
                ->orderByDesc('id')
                ->get();
        } elseif ($request->status == 'pending') {
            $data = UserOrders::with('user')
                ->with('table')
                ->where('vendor_id', $vendor_id)
                ->whereIn('order_status', ['confirmed', 'ongoing'])
                ->orderByDesc('id')
                ->get();
        } else {
            $data = UserOrders::with('user')
                ->with('table')
                ->where('vendor_id', $vendor_id)
                ->whereIn('order_status', ['in_process'])
                ->orderByDesc('id')
                ->get();
        }

        if (count($data) > 0) {
            foreach ($data as $key => $d) {
                $data[$key]['product'] = user_order_product::with(['product', 'variant', 'addons'])
                    ->where('order_id', $d->id)
                    ->get();
            }

            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['data'] = 'Order ID is not valid';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_orders_vendor(Request $request)
    {
        $vendor_id = Auth::user()->id;
        if (isset($request->status) && $request->status != '') {
            $status = $request->status;
            $data = UserOrders::with('user')
                ->with('table')
                ->where('vendor_id', $vendor_id)
                ->where('order_status', $status)
                ->orderBy('id', 'DESC')
                ->paginate(10);
        } else {
            $data = UserOrders::with('user')
                ->with('table')
                ->where('vendor_id', $vendor_id)
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }

        if (count($data) > 0) {
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['data'] = 'Order ID is not valid';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_orders_details_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required',
        ]);
        //return Auth::user()->id;

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $data = UserOrders::with('user')
            ->with('vendor')
            ->with('transactions')
            ->with('table')
            ->where('vendor_id', Auth::user()->id)
            ->where('order_code', $request->order_code)
            ->orderByDesc('id')
            ->get();
        if (count($data) > 0) {
            $order_id = $data[0]->id;
            $data[0]['cart'] = user_order_product::with(['product', 'variant', 'addons'])
                ->where('order_id', $order_id)
                ->get();
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['data'] = 'Order ID is not valid';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }
    public function verify_order_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $vendor_id = Auth::user()->id;
        $data = UserOrders::with('user')
            ->where('order_code', $request->order_id)
            ->where('vendor_id', $vendor_id)
            ->where('order_status', 'pending')
            ->get();
        if (count($data) > 0) {
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['data'] = 'Order ID is not valid';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function update_order_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required',
            'message' => 'required_if:status,!=,declined',
            'prepare_time' => 'required_if:status,==,in_process',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $vendor_id = Auth::user()->id;

        if ($request->status == 'in_process') {
            $estimate_time = $request->prepare_time;

            $new_time = date('Y-m-d H:i:s', strtotime('+' . $estimate_time . ' minutes', strtotime(date('Y-m-d H:i:s'))));
            $data = UserOrders::where('order_code', $request->order_id)
                ->where('vendor_id', $vendor_id)
                ->update(['order_status' => $request->status, 'estimate_prepare_time' => $new_time]);
        } elseif ($request->status == 'cancelled') {
            $data = UserOrders::where('order_code', $request->order_id)
                ->where('vendor_id', $vendor_id)
                ->update(['order_status' => $request->status]);

            $order_id = $request->order_id;
            $txn_status = user_orders_txn_log::whereIn('order_id', function ($q) use ($order_id) {
                $q->select('id')
                    ->from('user_orders')
                    ->where('order_code', $order_id);
            })->update(['txn_status' => 'Refunded']);
        } else {
            if ($request->status == 'processed') {
                $order_data = UserOrders::where('order_code', $request->order_id)->get(['user_id', 'order_type']);

                if ($order_data[0]->order_type == 'TakeAway') {
                    $request->status = 'completed';
                }

                $data = UserOrders::where('order_code', $request->order_id)
                    ->where('vendor_id', $vendor_id)
                    ->update(['order_status' => $request->status]);
            } else {
                $data = UserOrders::where('order_code', $request->order_id)
                    ->where('vendor_id', $vendor_id)
                    ->update(['order_status' => $request->status]);
            }
        }

        if ($data) {
            $response['status'] = true;

            $order_data = UserOrders::where('order_code', $request->order_id)->get(['user_id']);

            //send notification to vendor
            $heading_user = 'Your Order ' . $request->order_id . ' has been ' . $request->status;
            $post_url = env('NOTIFICATION_USER_URL') . '/ViewOrder/' . $request->order_id;

            KotstatusChannel::dispatch($vendor_id);
            ProcessPush::dispatch($heading_user, $post_url, $vendor_id, 'user', '');
            // OrderstatusChannel::dispatch($order_data[0]);

            // $heading_user = "Order has been accepted!";
        } elseif ($request->status != 'accept') {
            $response['status'] = false;
            $response['msg'] = $request->message;
            // $heading_user = $request->message;
        }

        //notification details
        // $post_url=env('NOTIFICATION_USER_URL')."/offer/".$maxid;
        // $desc = $request->offer_description;

        // //insert notification
        // ProcessPush::dispatch($heading_user,$post_url,$subscriber,"user",$desc);
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //Vendor Shop Visit
    public function vendor_shop_visit()
    {
        $id = Auth::user()->id;
        $data = Vendor_Shop_Visit::join('vendors', 'vendors.id', 'vendor_shop_visit.vendor_id')
            ->join('users', 'users.id', 'vendor_shop_visit.user_id')
            ->where('vendor_shop_visit.vendor_id', $id)
            ->where('vendor_shop_visit.user_activity', 'shop_visit')
            ->select(['users.name', 'users.profile_pic', 'vendor_shop_visit.created_at as time'])
            ->paginate(10);
        if (count($data) > 0) {
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No data found!';
        }

        return response()->json($response);
    }
    //Contact Details
    public function get_contacts_detail()
    {
        $id = Auth::user()->id;
        $data = Vendor_Shop_Visit::join('vendors', 'vendors.id', 'vendor_shop_visit.vendor_id')
            ->join('users', 'users.id', 'vendor_shop_visit.user_id')
            ->where('vendor_shop_visit.vendor_id', $id)
            ->where('vendor_shop_visit.user_activity', 'contact')
            ->select(['users.name', 'users.profile_pic', 'vendor_shop_visit.created_at as time'])
            ->paginate(10);
        if (count($data) > 0) {
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No data found!';
        }

        return response()->json($response);
    }

    //Saved Feed User Details
    public function get_saved_feed_user_detail()
    {
        $id = Auth::user()->id;
        $data = Feed::join('feed_saves', 'feed_saves.feed_id', 'feeds.id')
            ->where('feeds.vendor_id', $id)
            ->select(['feeds.id as feed_id'])
            ->addSelect(['username' => User::select('name')->whereColumn('id', 'feed_saves.user_id')])
            ->addSelect(['profile_pic' => User::select('profile_pic')->whereColumn('id', 'feed_saves.user_id')])
            ->paginate(10);
        if (count($data) > 0) {
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No data found!';
        }

        return response()->json($response);
    }
    //Vendor Feed View
    public function get_vendor_follower()
    {
        $id = Auth::user()->id;
        $data = Vendors_Subsciber::join('vendors', 'vendors.id', 'vendors_subscibers.vendor_id')
            ->join('users', 'users.id', 'vendors_subscibers.user_id')
            ->where('vendors_subscibers.vendor_id', $id)
            ->select(['users.name', 'users.profile_pic'])
            ->paginate(10);
        if (count($data) > 0) {
            $response['status'] = true;
            $response['data'] = $data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No data found!';
        }

        return response()->json($response);
    }
    public function edit_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;
        //return $vendor_id;

        $res = Vendor_category::find($request->category_id);
        if ($res->vendor_id == $vendor_id) {
            $res->name = $request->name;
            if ($res->save()) {
                $response['status'] = true;
                $response['msg'] = 'Category Updated!';
            } else {
                $response['status'] = false;
                $response['msg'] = 'Something went wrong!';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = "You don't have access to perforn this action!";
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_vendor_data_using_code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $data = Vendor::where('vendor_code', $request->vendor_code)->get();

        if (count($data) > 0) {
            $response['data'] = $data;
            $response['status'] = true;
        } else {
            $response['msg'] = 'InvalidCode';
            $response['status'] = false;
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //get store timing

    public function update_store_timing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $vendor_id = Auth::user()->id;
        $del = vendor_timing::where('vendor_id', $vendor_id)->delete();

        $data = [];
        foreach ($request->days as $key => $d) {
            $data[] = ['vendor_id' => $vendor_id, 'day_status' => $d['status'], 'day_name' => $d['day_name'], 'open_timing' => date('H:i:s', strtotime($d['open'])), 'close_timing' => date('H:i:s', strtotime($d['close']))];
        }

        if (vendor_timing::insert($data)) {
            $response['status'] = true;
            $response['msg'] = 'Timing Successfully Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Timing could not be updated!';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //get vendor notifications

    public function fetch_vendor_notification(Request $request)
    {
        $user_id = Auth::user()->id;
        $notifications = Notification::join('vendors', 'vendors.id', 'notifications.received_id')
            ->where('received_id', $user_id)
            ->where('receiver_type', 'vendor')
            ->orderBy('notifications.id', 'DESC')
            ->paginate(10);

        $response['status'] = true;
        $response['data'] = $notifications;

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //get user profile
    public function get_vendor_profile(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = Vendor::with('timings')
            ->with('covers')
            ->where('id', $user_id)
            ->get();

        if (count($user) > 0) {
            if ($user[0]->name == ' ' || $user[0]->name == null) {
                $steps = 'steps';
            } else {
                $steps = 'done';
            }

            $response['status'] = true;
            $response['data'] = $user;
            $response['step'] = $steps;
            $response['link'] = env('APP_URL') . '/' . $user[0]->id . '/takeawaylisting';
            $x = 0;
            if ($user[0]->profile_pic == null) {
                $x = $x + 1;
                $response['data'][0]['step'] = $x;
            }

            $cover = Vendor_cover::where('vendor_id', $user_id)->get();

            if (count($cover) == 0) {
                $x = $x + 1;
                $response['data'][0]['step'] = $x;
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid token';
        }

        return json_encode($response);
    }

    //function for update profile of user
    public function update_profile_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'nullable|email',
            //'shop_name'=>'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        if (isset($request->update_type)) {
            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';

            $name = substr(str_replace(' ', '', $request->name), 0, 4);
            //return $name;
            $rand = substr(str_shuffle($str_result), 0, 6);
            $user->share_code = strtoupper($name . $rand);
        }

        $user = vendor::find($vendor_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->shop_name = $request->name;
        $user->description = $request->description;

        $user->whatsapp = $request->whatsapp;
        $user->website = $request->website;
        if ($user->save()) {
            $response['status'] = true;
            $response['msg'] = 'Profile successfully updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Profile could not be updated!';
        }

        return json_encode($response);
    }

    //function for call profile pic
    public function update_profile_picture_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'update_profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        //condition to check iF file exits or not
        if ($request->hasFile('update_profile_picture')) {
            $pic = $request->file('update_profile_picture');

            // $current_pic=Auth::user()->profile_pic;

            // //code for delete the file from storage
            // $nf= str_replace(env('APP_CDN_URL'),'',$current_pic);
            // Storage::disk(env('DEFAULT_STORAGE'))->delete($nf);

            //object to upload the file
            $globalclass = new GlobalController();

            //Remove Previous Image
            $globalclass->removeprevious();

            $path = 'shop_pic/';

            $res = $globalclass->upload_img($pic, $path);

            if (!$res['status']) {
                $response['status'] = false;
                $response['msg'] = 'Profile could not be updated!';
            } else {
                $name = $res['file_name'];
                $vendor_id = Auth::user()->id;
                $user = vendor::find($vendor_id);
                $user->profile_pic = $res['file_name'];

                if ($user->save()) {
                    $response['status'] = true;
                    $response['profile_pic'] = $res['file_name'];
                } else {
                    $response['status'] = false;
                    $response['msg'] = 'Profile could not be updated!';
                }
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid File';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //function for call cover pictures
    public function update_cover_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover_picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        //condition to check iF file exits or not
        if ($request->hasFile('cover_picture')) {
            $pic = $request->file('cover_picture');

            $globalclass = new GlobalController();
            $path = 'shop_pic/';

            $res = $globalclass->upload_img($pic, $path);

            if ($res['status']) {
                $name = $res['file_name'];
                $vendor_id = Auth::user()->id;
                $user = new vendor_cover();
                $user->image = $name;
                $user->vendor_id = $vendor_id;
                $user->status = 'active';

                if ($user->save()) {
                    $response['status'] = true;
                    $response['profile_pic'] = $name;
                } else {
                    $response['status'] = false;
                    $response['msg'] = 'Profile could not be updated!';
                }
            } else {
                $response['status'] = false;
                $response['msg'] = 'Profile could not be updated!';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid File';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //get cover vendorss
    public function get_cover_vendor(Request $request)
    {
        $vendor_id = Auth::user()->id;
        $response['covers'] = Vendor_cover::where('vendor_id', $vendor_id)->get();

        $response['status'] = true;
        return json_encode($response);
    }

    //function for update the vendor category

    public function update_main_category_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $data = [];
        $vendor_id = Auth::user()->id;

        if (vendor_main_categories::where('vendor_id', $vendor_id)->delete()) {
            foreach ($request->category_id as $cat) {
                $data[] = ['vendor_id' => $vendor_id, 'category_id' => $cat];
            }

            if (vendor_main_categories::insert($data)) {
                $response['status'] = true;
                $response['msg'] = 'Category Successfully Updated!';
            } else {
                $response['status'] = false;
                $response['msg'] = 'Category could not be updated!';
            }
        } else {
            foreach ($request->category_id as $cat) {
                $data[] = ['vendor_id' => $vendor_id, 'category_id' => $cat];
            }

            if (vendor_main_categories::insert($data)) {
                $response['status'] = true;
                $response['msg'] = 'Category Successfully Updated!';
            } else {
                $response['status'] = false;
                $response['msg'] = 'Category could not be updated!';
            }
        }
        return json_encode($response);
    }

    //function for update the product or packages status
    public function update_product_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'product_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $data = [];
        $vendor_id = Auth::user()->id;

        $pp = Vendor_Product::find($required->product_id);

        $pp->status = $request->product_status;

        if ($pp->save()) {
            $response['status'] = true;
            $response['msg'] = 'Successfully Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = ' could not be updated!';
        }

        return json_encode($response);
    }

    public function create_category_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        $category = new Vendor_category();

        $category->vendor_id = $vendor_id;
        $category->name = $request->category_name;
        $category->status = $request->status;

        if ($category->save()) {
            $response['status'] = true;
            $response['msg'] = 'Category Successfully Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Category could not be updated!';
        }
        return json_encode($response);
    }

    public function update_category_vendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_status' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $category_id = $request->category_id;

        $category = Vendor_category::find($category_id);

        $category->name = $request->category_name;
        $category->status = $request->category_status;

        if ($category->save()) {
            $response['status'] = true;
            $response['msg'] = 'Category Successfully Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Category could not be updated!';
        }
        return json_encode($response);
    }

    public function update_store_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'area' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
            'pincode' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        $vendor = Vendor::find($vendor_id);

        $vendor->city = $request->city;
        $vendor->area = $request->area;
        $vendor->state = $request->state;
        $vendor->address = $request->address;
        $vendor->shop_latitude = $request->latitude;
        $vendor->shop_longitude = $request->longitude;
        $vendor->pincode = $request->pincode;
        $vendor->shop_no = $request->shop_no;
        if ($vendor->save()) {
            $response['status'] = true;
            $response['msg'] = 'Address Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Address could not be updated!';
        }
        return json_encode($response);
    }

    //for add the product or services

    public function vendor_add_product(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'vendor_category_id' => 'required',
            'market_price' => 'required',
            'price' => 'required',
            'product_img' => 'required',
            'type' => 'required',
            'is_veg' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->market_price < $request->price) {
            $response['status'] = false;
            $response['msg'] = 'Market Price should be greater than Our Price!';
            return json_encode($response);
        }
        $vendor_id = Auth::user()->id;

        //condition to check iF file exits or not
        if ($request->hasFile('product_img')) {
            $pic = $request->file('product_img');
            $path = 'products/';

            $globalclass = new GlobalController();

            $res = $globalclass->upload_img($pic, $path);

            if ($res['status']) {
                $path = $res['file_name'];

                $v_product = new Vendor_Product();
                $v_product->product_name = $request->product_name;
                $v_product->market_price = $request->market_price;
                $v_product->our_price = $request->price;
                $v_product->description = $request->description;
                $v_product->status = 'active';
                $v_product->vendor_id = $vendor_id;
                $v_product->vendor_category_id = $request->vendor_category_id;
                $v_product->product_img = $path;
                $v_product->type = $request->type;
                $v_product->is_veg = $request->is_veg;
                if ($v_product->save()) {
                    $product_id = $v_product->id;
                    $product = Vendor_Product::with('variants')
                        ->with('addons')
                        ->find($product_id);

                    $response['status'] = true;
                    $response['msg'] = 'Product Added!';
                    $response['data'] = $product;
                } else {
                    $response['status'] = false;
                    $response['msg'] = 'Product could not be Added!';
                }
            } else {
                $response['status'] = false;
                $response['msg'] = 'img could not be updated!';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid File';
        }
        return json_encode($response);
    }

    //get_selected_category_vendor

    public function get_selected_category_vendor(Request $request)
    {
        $vendor_id = Auth::user()->id;

        $cat = Category::whereIn('id', function ($q) use ($vendor_id) {
            $q->from('vendor_main_categories')
                ->select('category_id')
                ->where('vendor_id', $vendor_id);
        })->get();

        $response['status'] = true;
        $response['data'] = $cat;
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //fetch vendor_category

    public function fetch_vendor_category(Request $request)
    {
        $vendor_id = Auth::user()->id;

        $cat = Vendor_category::withCount('products')
            ->where('status', 'active')
            ->where('vendor_id', $vendor_id)
            ->get();

        if (sizeof($cat) > 0) {
            $response['status'] = true;
            $response['data'] = $cat;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No Data Found';
        }
        return json_encode($response);
    }

    //update vendor servicess

    public function vendor_update_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'vendor_category_id' => 'required',
            'market_price' => 'required',
            'price' => 'required',
            'product_id' => 'required',
            'type' => 'required',
            'is_veg' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->market_price < $request->price) {
            $response['status'] = false;
            $response['msg'] = 'Market Price should be greater than Our Price!';
            return json_encode($response);
        }

        $vendor_id = Auth::user()->id;

        //condition to check iF file exits or not
        if ($request->hasFile('product_img')) {
            $pic = $request->file('product_img');
            $path = 'products/';

            $globalclass = new GlobalController();

            $res = $globalclass->upload_img($pic, $path);

            if ($res['status']) {
                $current_pic = Vendor_Product::find($request->product_id);
                //code for delete the file from storage
                $nf = str_replace(env('APP_CDN_URL'), '', $current_pic->product_img);
                Storage::disk(env('DEFAULT_STORAGE'))->delete($nf);

                $path = $res['file_name'];

                $v_product = Vendor_Product::find($request->product_id);
                $v_product->product_name = $request->product_name;
                $v_product->market_price = $request->market_price;
                $v_product->our_price = $request->price;
                $v_product->description = $request->description;
                $v_product->status = 'active';
                $v_product->vendor_id = $vendor_id;
                $v_product->vendor_category_id = $request->vendor_category_id;
                $v_product->product_img = $path;
                $v_product->type = $request->type;
                $v_product->is_veg = $request->is_veg;
                if ($v_product->save()) {
                    $response['status'] = true;
                    $response['msg'] = 'Product Updated!';
                } else {
                    $response['status'] = false;
                    $response['msg'] = 'Product could not be Added!';
                }
            } else {
                $response['status'] = false;
                $response['msg'] = 'img could not be updated!';
            }
        } else {
            $v_product = Vendor_Product::find($request->product_id);
            $v_product->product_name = $request->product_name;
            $v_product->market_price = $request->market_price;
            $v_product->our_price = $request->price;
            $v_product->description = $request->description;
            $v_product->status = 'active';
            $v_product->vendor_id = $vendor_id;
            $v_product->vendor_category_id = $request->vendor_category_id;
            $v_product->type = $request->type;
            $v_product->is_veg = $request->is_veg;

            if ($v_product->save()) {
                $response['status'] = true;
                $response['msg'] = 'Product Added!';
            } else {
                $response['status'] = false;
                $response['msg'] = 'Product could not be Added!';
            }
        }
        return json_encode($response);
    }

    //update vendor production options

    public function vendor_update_product_options(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        vendor_products_variant::where('product_id', $request->product_id)->delete();
        vendor_product_adons_map::where('product_id', $request->product_id)->delete();
        if (isset($request->variants)) {
            $data = [];
            $x = 0;

            foreach ($request->variants as $da) {
                $data[] = ['product_id' => $request->product_id, 'variants_name' => $da['variants_name'], 'variants_price' => $da['variants_price'], 'variants_discounted_price' => $da['variants_discounted_price']];
                //                            $data[$x]['product_id'] =$request->product_id;
                //                            $data[$x]['variants_name'] =$da['variants_name'];
                //                            $data[$x]['variants_price'] =$da['variants_price'];
                // $data[$x]['variants_discounted_price'] =$da['variants_discounted_price'];
                $x++;
            }

            vendor_products_variant::insertOrIgnore($data);
        }

        if (isset($request->addons)) {
            $data2 = [];
            $x = 0;
            foreach ($request->addons as $da) {
                $data2[$x]['product_id'] = $request->product_id;
                $data2[$x]['addon_id'] = $da;
                $x++;
            }

            vendor_product_adons_map::insert($data2);
        }

        $response['status'] = true;
        $response['msg'] = 'Product Updated!';

        return json_encode($response);
    }

    public function add_vendor_offer(Request $request)
    {
        //return $request;
        //return Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'offer_name' => 'required',
            // 'offer_type'=> 'required',
            'offer' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            // 'vendor_id'=>'required',
            'offer_description' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $offer = new Vendor_Offer();
        $vendor_id = Auth::user()->id;
        $offer->offer_name = $request->offer_name;
        // $offer->offer_type=$request->offer_type;
        $offer->offer = $request->offer;
        $offer->start_from = date('Y-m-d', strtotime($request->start_date));
        $offer->start_to = date('Y-m-d', strtotime($request->end_date));
        $offer->status = 'active';
        $offer->vendor_id = $vendor_id;
        $offer->offer_description = 'hi';
        $offer->offer_description = $request->offer_description;
        if ($offer->save()) {
            $offer_id = $offer->id;
            $data = [];
            if (isset($request->products)) {
                foreach ($request->products as $pp) {
                    $data[] = ['offer_id' => $offer_id, 'product_id' => $pp];
                }
            }

            if (isset($request->products)) {
                foreach ($request->packages as $pp) {
                    $data[] = ['offer_id' => $offer_id, 'product_id' => $pp];
                }
            }

            if (Vendor_Offer_Product::insert($data)) {
                $response['status'] = true;
                $response['msg'] = 'Offer Added!';
                $maxid = Vendor_Offer_Product::max('offer_id');
                // $subscriber = Vendors_Subsciber::where('vendor_id',Auth::user()->id)->get(['user_id'])->toArray();

                //notification details
                //				$heading_user= Auth::user()->name." has created an offer.";
                //				$post_url=env('NOTIFICATION_USER_URL')."/offer/".$maxid;
                //				$desc = $request->offer_description;

                //insert notification
                //				ProcessPush::dispatch($heading_user,$post_url,$subscriber,"user",$desc);
            } else {
                $response['status'] = false;
                $response['msg'] = 'Offer could not be Added!';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Offer could not be Added!';
        }

        return json_encode($response);
    }

    public function update_vendor_offer(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'offer_name' => 'required',
            //'offer_type'=> 'required',
            'offer' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            //'vendor_id'=>'required',
            'offer_id' => 'required',
            'offer_description' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $offer = Vendor_Offer::find($request->offer_id);
        $offer->offer_name = $request->offer_name;
        $offer->offer_type = $request->offer_type;
        $offer->offer = $request->offer;
        $offer->start_from = date('Y-m-d', strtotime($request->start_date));
        $offer->start_to = date('Y-m-d', strtotime($request->end_date));
        // $offer->status =$request->offer_name;
        $offer->offer_description = $request->offer_description;
        if ($offer->save()) {
            if (Vendor_Offer_Product::where('offer_id', $request->offer_id)->delete()) {
                $offer_id = $request->offer_id;
                $data = [];

                if (isset($request->product)) {
                    foreach ($request->products as $pp) {
                        $data[] = ['offer_id' => $offer_id, 'product_id' => $pp];
                    }
                }

                if (isset($request->packages)) {
                    foreach ($request->packages as $pp) {
                        $data[] = ['offer_id' => $offer_id, 'product_id' => $pp];
                    }
                }
                if (Vendor_Offer_Product::insert($data)) {
                    $response['status'] = true;
                    $response['msg'] = 'Offer Updated!';
                } else {
                    $response['status'] = false;
                    $response['msg'] = 'Offer could not be Updated!';
                }
            } else {
                $offer_id = $request->offer_id;
                $data = [];
                if (isset($request->product)) {
                    foreach ($request->products as $pp) {
                        $data[] = ['offer_id' => $offer_id, 'product_id' => $pp];
                    }
                }

                if (isset($request->packages)) {
                    foreach ($request->packages as $pp) {
                        $data[] = ['offer_id' => $offer_id, 'product_id' => $pp];
                    }
                }

                if (Vendor_Offer_Product::insert($data)) {
                    $response['status'] = true;
                    $response['msg'] = 'Offer Updated!';
                } else {
                    $response['status'] = false;
                    $response['msg'] = 'Offer could not be Updated!';
                }
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Offer could not be Updated!';
        }

        return json_encode($response);
    }

    public function get_category_vendor(Request $request)
    {
        //return "Hello";
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $cat = Vendor_category::where('status', 'active')
            ->where('vendor_id', $request->vendor_id)
            ->get();

        if (sizeof($cat) > 0) {
            $response['status'] = true;
            $response['data'] = $cat;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No Data Found';
        }
        return json_encode($response);
    }

    public function get_vendor_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        //	$user_id=Auth::user()->id;
        //return $request->vendor_id;
        //fetch store details of vendor
        //  return $request->vendor_id;
        $store_data = Vendor::with('covers')
            ->with('categories')
            ->with('shop_timing')
            ->with('today_timing')
            ->where('id', $request->vendor_id)
            ->get();

        if (count($store_data) > 0) {
            if ($store_data[0]->status == 'active') {
                $response['status'] = true;
                $response['data'] = $store_data;
            } else {
                $response['status'] = false;
                $response['data'] = 'Vendor is not active';
            }

            //$response['distance']=$distance;
            //$response['categories']=Vendor_category::with('products')->where('vendor_id',$request->vendor_id)->where('status','active')->get();
            // $response['shop_timing']=vendor_timing::where('vendor_id',$request->vendor_id)->where('day_status','1')->get(['day_name','open_timing','close_timing']);
            //$response['data'][0]['followers']=Vendors_Subsciber::where('vendor_id',$request->vendor_id)->count();
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Vendor Id, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_vendor_details_by_table(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        //return $request->table_id;
        $tables = vendor_table::where('table_uu_id', $request->table_id)->get();

        if (count($tables) == 0) {
            $response['status'] = false;
            $response['msg'] = 'Invalid Vendor Id, Try Again.';
            return json_encode($response, JSON_UNESCAPED_SLASHES);
        }
        $table_id = $tables[0]->id;

        $table = vendor_table::where('id', $table_id)->get();

        if (count($table) == 0) {
            $response['status'] = false;
            $response['msg'] = 'Invalid Table Id, Try Again.';
            return json_encode($response, JSON_UNESCAPED_SLASHES);
        }
        $vendor_id = $table[0]->vendor_id;

        //fetch store details of vendor
        //  return $request->vendor_id;
        $store_data = Vendor::with('covers')
            ->with('shop_timing')
            ->with('today_timing')
            ->where('vendors.status', 'active')
            ->where('id', $vendor_id)
            ->get();

        if (count($store_data) > 0) {
            $response['status'] = true;
            $response['data'] = $store_data;
            //$response['distance']=$distance;
            $response['categories'] = Vendor_category::where('vendor_id', $vendor_id)
                ->where('status', 'active')
                ->get();
            // $response['shop_timing']=vendor_timing::where('vendor_id',$request->vendor_id)->where('day_status','1')->get(['day_name','open_timing','close_timing']);
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Vendor Id, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    // update status product or offer
    public function update_status_product_offer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action_id' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->type == 'product') {
            $v_product = Vendor_Product::find($request->action_id);

            $v_product->status = $request->status;

            if ($v_product->save()) {
                $response['status'] = true;
                $response['msg'] = 'Product updated!';
            } else {
                $response['status'] = false;
                $response['msg'] = 'Product could not be updated!';
            }
        } elseif ($request->type == 'package') {
            $v_product = Vendor_Product::find($request->action_id);

            $v_product->status = $request->status;

            if ($v_product->save()) {
                $response['status'] = true;
                $response['msg'] = 'Product updated!';
            } else {
                $response['status'] = false;
                $response['msg'] = 'Product could not be updated!';
            }
        } elseif ($request->type == 'offer') {
            $offer = Vendor_Offer::find($request->action_id);

            $offer->status = $request->status;
            if ($offer->save()) {
                $response['status'] = true;
                $response['msg'] = 'updated!';
            } else {
                $response['status'] = false;
                $response['msg'] = 'Not updated!';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Request';
        }
        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function search_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_query' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;
        $q = $request->search_query;

        //	return $request->vendor_id;
        $search_product = Vendor_Product::with('variants')
            ->with('addon_map')
            ->where('product_name', 'like', '%' . $q . '%')
            ->where('vendor_id', $vendor_id)
            ->limit(5)
            ->get();
        //			$search_vendor=Vendor::where('status','Active')->where('shop_name','like', '%' . $q . '%')->limit(5)->get();
        $response['data'] = $search_product;
        //			$response['vendor']=$search_vendor;

        $response['status'] = true;

        return json_encode($response);
    }

    public function get_vendor_product(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'vendor_category_id' => 'required',
            'product_type' => 'required',
            'is_veg' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        //return $request_category_id;
        if ($request->vendor_category_id != 0 && $request->product_type == 'product') {
            if ($request->is_veg != 'all') {
                //fetch store details of vendor
                $store_data = Vendor_Product::with('variants')
                    ->with('addon_map')
                    ->with('favourite')
                    ->where('vendor_category_id', $request->vendor_category_id)
                    ->where('is_veg', $request->is_veg)
                    ->where('status', 'active')
                    ->paginate(30);
            } else {
                //fetch store details of vendor
                $store_data = Vendor_Product::with('variants')
                    ->with('addon_map')
                    ->with('favourite')
                    ->where('vendor_category_id', $request->vendor_category_id)
                    ->where('status', 'active')
                    ->paginate(30);
            }
        } elseif ($request->vendor_category_id == 0 && $request->product_type == 'product') {
            if (isset($request->vendor_id)) {
                if ($request->is_veg != 'all') {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('is_veg', $request->is_veg)
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(30);
                } else {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('status', 'active')
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(30);
                }
            } else {
                if ($request->is_veg != 'all') {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->paginate(30);
                } else {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('is_veg', $request->is_veg)
                        ->paginate(30);
                }
            }
            //fetch store details of vendor
        } elseif ($request->vendor_category_id != 0 && $request->product_type == 'package') {
            if (isset($request->vendor_id)) {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(30);
                } else {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('vendor_id', $request->vendor_id)
                        ->where('is_veg', $request->is_veg)
                        ->paginate(30);
                }
            } else {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->paginate(30);
                } else {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('is_veg', $request->is_veg)
                        ->paginate(30);
                }
            }
        } else {
            if (isset($request->vendor_id)) {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(30);
                } else {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('vendor_id', $request->vendor_id)
                        ->where('is_veg', $request->is_veg)
                        ->paginate(30);
                }
            } else {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->paginate(30);
                } else {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->where('is_veg', $request->is_veg)
                        ->paginate(30);
                }
            }
        }

        // return $store_data;
        // exit;
        if (count($store_data) > 0) {
            $response['status'] = true;
            $response['data'] = $store_data;
        } else {
            $response['status'] = false;
            $response['data'] = [];
            $response['msg'] = 'Invalid Category, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //get vendor product by auth

    public function get_vendor_product_Auth(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'vendor_category_id' => 'required',
            'product_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user_id = Auth::user()->id;
        //return $request_category_id;
        if ($request->vendor_category_id != 0 && $request->product_type == 'product') {
            if ($request->is_veg != 'all') {
                //fetch store details of vendor
                $store_data = Vendor_Product::with('variants')
                    ->with('addon_map')
                    ->addSelect([
                        'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                            ->whereColumn('product_id', 'vendor_products.id')
                            ->where('user_id', $user_id),
                    ])
                    ->where('vendor_category_id', $request->vendor_category_id)
                    ->where('is_veg', $request->is_veg)
                    ->where('status', 'active')
                    ->paginate(10);
            } else {
                $store_data = Vendor_Product::with('variants')
                    ->with('addon_map')
                    ->addSelect([
                        'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                            ->whereColumn('product_id', 'vendor_products.id')
                            ->where('user_id', $user_id),
                    ])
                    ->where('vendor_category_id', $request->vendor_category_id)
                    ->where('status', 'active')
                    ->paginate(10);
            }
        } elseif ($request->vendor_category_id == 0 && $request->product_type == 'product') {
            if (isset($request->vendor_id)) {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->whereIn('status', ['active'])
                        ->where('is_veg', $request->is_veg)
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(10);
                } else {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->whereIn('status', ['active'])
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(10);
                }
            } else {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active'])
                        ->where('is_veg', $request->is_veg)
                        ->paginate(10);
                } else {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active'])
                        ->paginate(10);
                }
            }
        } elseif ($request->vendor_category_id != 0 && $request->product_type == 'package') {
            if (isset($request->vendor_id)) {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->where('vendor_id', $request->vendor_id)
                        ->where('is_veg', $request->is_veg)
                        ->paginate(10);
                } else {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(10);
                }
            } else {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->where('is_veg', $request->is_veg)
                        ->paginate(10);
                } else {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->paginate(10);
                }
            }
        } else {
            if (isset($request->vendor_id)) {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->where('is_veg', $request->is_veg)
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(10);
                } else {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->where('vendor_id', $request->vendor_id)
                        ->paginate(10);
                }
            } else {
                if ($request->is_veg != 'all') {
                    //fetch store details of vendor
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('is_veg', $request->is_veg)
                                ->where('user_id', $user_id),
                        ])
                        ->paginate(10);
                } else {
                    $store_data = Vendor_Product::with('favourite')
                        ->with('variants')
                        ->with('addon_map')
                        ->where('type', $request->product_type)
                        ->whereIn('status', ['active', 'inactive'])
                        ->addSelect([
                            'is_cart' => user_cart::selectRaw('sum(product_quantity)')
                                ->whereColumn('product_id', 'vendor_products.id')
                                ->where('user_id', $user_id),
                        ])
                        ->paginate(10);
                }
            }
        }
        // return $store_data;
        // exit;
        if (count($store_data) > 0) {
            $response['status'] = true;
            $response['data'] = $store_data;
        } else {
            $response['status'] = false;
            $response['data'] = [];
            $response['msg'] = 'Invalid Category, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_product_details(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $product_id = $request->product_id;

        $store_data = Vendor_Product::with(['variants'])
            ->with('addon_map')
            ->where('id', $product_id)
            ->where('status', '!=', 'delete')
            ->get();

        if (count($store_data) > 0) {
            $response['status'] = true;
            $response['data'] = $store_data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Product Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_vendor_product_vendor(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'vendor_category_id' => 'required',
            'product_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;
        $type = $request->product_type;
        //return $vendor_id;
        //return $request_category_id;

        if (isset($request->status)) {
            if ($request->vendor_category_id != 0) {
                //fetch store details of vendor
                $store_data = Vendor_Product::with(['variants'])
                    ->with('category')
                    ->with('addon_map')
                    ->where('vendor_category_id', $request->vendor_category_id)
                    ->where('type', $request->product_type)
                    ->where('vendor_id', $vendor_id)
                    ->where('status', $request->status)
                    ->paginate(30);
            } else {
                //fetch store details of vendor
                $store_data = Vendor_Product::with('variants')
                    ->with('category')
                    ->with('addon_map')
                    ->where('type', $request->product_type)
                    ->where('vendor_id', $vendor_id)
                    ->where('status', $request->status)
                    ->paginate(30);
            }
        } else {
            if ($request->vendor_category_id != 0) {
                //fetch store details of vendor
                $store_data = Vendor_Product::with(['variants'])
                    ->with('category')
                    ->with('addon_map')
                    ->where('vendor_category_id', $request->vendor_category_id)
                    ->where('type', $request->product_type)
                    ->where('vendor_id', $vendor_id)
                    ->where('status', '!=', 'delete')
                    ->paginate(30);
            } else {
                //fetch store details of vendor
                $store_data = Vendor_Product::with('variants')
                    ->with('category')
                    ->with('addon_map')
                    ->where('type', $request->product_type)
                    ->where('vendor_id', $vendor_id)
                    ->where('status', '!=', 'delete')
                    ->paginate(30);
            }
        }

        // return $store_data;
        // exit;
        if (count($store_data) > 0) {
            $response['status'] = true;
            $response['data'] = $store_data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Category, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_vendor_offers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = $request->vendor_id;

        $haversine =
            '(6371 * acos(cos(radians(' .
            $request->latitude .
            "))
        * cos(radians(`shop_latitude`))
        * cos(radians(`shop_longitude`)
        - radians(" .
            $request->longitude .
            "))
        + sin(radians(" .
            $request->latitude .
            "))
        * sin(radians(`shop_latitude`))))";

        $day = date('Y-m-d');
        //confitions for check all the users
        if ($vendor_id != 0) {
            $offer_data = Vendor_Offer::with('vendor')
                ->where('status', 'active')
                ->where('start_from', '<=', $day)
                ->where('start_to', '>=', date('Y-m-d'))
                ->where('vendor_id', $vendor_id)
                ->paginate(10);
        } else {
            if ($request->category_id != 0) {
                $cate_id = $request->category_id;
                $offer_data = Vendor::where('vendors.status', 'active')
                    ->join('vendor_offers', 'vendor_offers.vendor_id', 'vendors.id')
                    ->where('start_from', '<=', $day)
                    ->where('start_to', '>=', date('Y-m-d'))
                    ->select(['vendors.*', 'vendor_offers.offer_description', 'vendor_offers.offer_name', 'vendor_offers.offer', 'vendor_offers.start_from', 'vendor_offers.start_to', 'vendor_offers.id as offer_id'])
                    ->whereIn('vendors.id', function ($q) use ($cate_id) {
                        $q->from('vendor_main_categories')
                            ->selectRaw('vendor_id')
                            ->where('category_id', $cate_id);
                    })
                    ->where('vendor_offers.status', 'active')
                    ->selectRaw("{$haversine} AS distance")
                    ->having('distance', '<', '25')
                    ->orderBy('distance')
                    ->paginate(10);
            } else {
                $offer_data = Vendor::where('vendors.status', 'active')
                    ->join('vendor_offers', 'vendor_offers.vendor_id', 'vendors.id')
                    ->where('start_from', '<=', $day)
                    ->where('start_to', '>=', date('Y-m-d'))
                    ->select(['vendors.*', 'vendor_offers.offer_description', 'vendor_offers.offer_name', 'vendor_offers.offer', 'vendor_offers.start_from', 'vendor_offers.start_to', 'vendor_offers.id as offer_id'])
                    ->selectRaw("{$haversine} AS distance")
                    ->having('distance', '<', '25')
                    ->where('vendor_offers.status', 'active')
                    ->orderBy('distance')
                    ->paginate(10);
            }
            // return $offer_data;
        }

        foreach ($offer_data as $key => $o) {
            $offer_id = $o->offer_id;
            $offer_data[$key]['products'] = Vendor_Product::whereIn('id', function ($q) use ($offer_id) {
                $q->from('vendor_offer_products')
                    ->selectRaw('product_id')
                    ->whereIn('offer_id', [$offer_id]);
            })->get();
        }
        //return $store_data;

        if (count($offer_data) > 0) {
            $response['status'] = true;
            $response['data'] = $offer_data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Category, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function get_vendor_offers_single(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $offer_id = $request->offer_id;

        //confitions for check all the users

        $offer_data = Vendor::join('vendor_offers', 'vendor_offers.vendor_id', 'vendors.id')
            ->whereDate('vendor_offers.start_to', '>=', date('Y-m-d'))
            ->select(['vendors.*', 'vendor_offers.offer_description', 'vendor_offers.offer_name', 'vendor_offers.offer', 'vendor_offers.start_from', 'vendor_offers.start_to', 'vendor_offers.id as offer_id'])
            ->where('vendor_offers.id', $offer_id)
            ->where('vendors.status', 'active')
            ->where('vendor_offers.status', '!=', 'delete')
            ->get();
        foreach ($offer_data as $key => $o) {
            $offer_id = $o->offer_id;
            $offer_data[$key]['products'] = Vendor_Product::whereIn('id', function ($q) use ($offer_id) {
                $q->from('vendor_offer_products')
                    ->select('product_id')
                    ->where('offer_id', $offer_id);
            })->get();
        }
        //return $store_data;

        if (count($offer_data) > 0) {
            $response['status'] = true;
            $response['data'] = $offer_data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Category, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    //delete cover pictures
    //get cover vendorss
    public function delete_cover_vendor(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'cover_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $vendor_id = Auth::user()->id;

        $current_pic = Vendor_cover::find($request->cover_id);
        $res = Vendor_cover::where('id', $request->cover_id)
            ->where('vendor_id', Auth::user()->id)
            ->delete();

        if ($res) {
            //code for delete the file from storage
            $nf = str_replace(env('APP_CDN_URL'), '', $current_pic->image);
            Storage::disk(env('DEFAULT_STORAGE'))->delete($nf);

            $response['status'] = true;
            $response['msg'] = 'delete';
        } else {
            $response['status'] = false;
            $response['msg'] = 'not permitted';
        }

        return json_encode($response);
    }

    //fetch vendor_offers
    public function get_vendor_offers_vendor(Request $request)
    {
        //return "Hello";
        //return $request;
        $vendor_id = Auth::user()->id;
        //return $vendor_id;
        $offer_data = Vendor_Offer::where('vendor_id', $vendor_id)
            ->whereDate('start_to', '>=', date('Y-m-d'))
            ->where('status', '!=', 'delete')
            ->get();

        foreach ($offer_data as $key => $o) {
            $offer_id = $o->id;
            $offer_data[$key]['products'] = Vendor_Product::whereIn('id', function ($q) use ($offer_id) {
                $q->from('vendor_offer_products')
                    ->selectRaw('product_id')
                    ->whereIn('offer_id', [$offer_id]);
            })->get();
        }

        //return count($offer_data);
        if (count($offer_data) > 0) {
            $response['status'] = true;
            $response['data'] = $offer_data;
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid Category, Try Again.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function update_shop_visit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'update_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user_id = Auth::user()->id;

        $shop_visit = new Vendor_Shop_Visit();

        $shop_visit->user_id = $user_id;
        $shop_visit->vendor_id = $request->vendor_id;
        $shop_visit->user_activity = $request->update_type;
        if ($shop_visit->save()) {
            $response['status'] = true;
            $response['msg'] = 'Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'not updated!';
        }
        return json_encode($response);
    }

    public function vendorReviewsRating()
    {
        $vendor_id = Auth::user()->id;

        $vr = vendor_rating::with('user')
            ->with('vendor')
            ->where('vendor_ratings.vendor_id', $vendor_id)
            ->orderBy('id', 'DESC')
            ->get();

        $total_rating = count($vr);
        //$vr=vendor_rating::selectRaw('*,count(vendor_ratings.*')->where('vendor_ratings.vendor_id',$vendor_id)->get();

        $rating_per = vendor_rating::selectRaw('count(vendor_rating)/' . $total_rating . ' as percentage,vendor_rating')
            ->where('vendor_id', $vendor_id)
            ->groupBy('vendor_rating')
            ->orderBy('vendor_rating')
            ->get();
        //$vr=vendor_rating::selectRaw('count(*) as cc')->addSelect(['rate1' =>vendor_rating::selectRaw('count(*)/cc')->whereColumn('vendor_id', 'vendor_ratings.vendor_id')->where('vendor_rating',5)])->where('vendor_ratings.vendor_id',$vendor_id)->get();

        if (count($vr) > 0) {
            $response['status'] = true;
            $response['data'] = $vr;
            $response['data'][0]['rating_percentage'] = $rating_per;
        } else {
            $response['status'] = true;
            $response['msg'] = 'No data found.';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function update_flat_deals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_time' => 'required',
            'all_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;

        $user = vendor::find($vendor_id);

        $user->flat_deal_first_time = $request->first_time;
        $user->flat_deal_all_time = $request->all_time;
        if ($user->save()) {
            $response['status'] = true;
            $response['msg'] = 'Profile successfully updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Profile could not be updated!';
        }

        return json_encode($response);
    }

    //picku[ points]

    public function add_pickup_points(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickuppoint_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $vendor_id = Auth::user()->id;
        $pickup_point = new vendor_pickup_point();
        $pickup_point->vendor_id = $vendor_id;
        $pickup_point->pickuppoint_name = $request->pickuppoint_name;

        if ($pickup_point->save()) {
            $response['status'] = true;
            $response['msg'] = 'Pickup Point Added!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Pickup Point could not be added!';
        }

        return json_encode($response);
    }

    public function fetch_pickup_point()
    {
        $vendor_id = Auth::user()->id;
        $pickup_point = vendor_pickup_point::where('vendor_id', $vendor_id)->get();
        if (count($pickup_point) > 0) {
            $response['status'] = true;
            $response['data'] = $pickup_point;
        } else {
            $response['status'] = false;
            $response['msg'] = 'No Pickup Point Found!';
        }

        return json_encode($response, JSON_UNESCAPED_SLASHES);
    }

    public function pickup_points_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_point_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $pickup_point = vendor_pickup_point::find($request->pickup_point_id);
        if ($pickup_point->delete()) {
            $response['status'] = true;
            $response['msg'] = 'Pickup Point Deleted!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Pickup Point could not be deleted!';
        }

        return json_encode($response);
    }

    public function pickup_points_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_point_id' => 'required',
            'pickup_point_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $pickup_point = vendor_pickup_point::find($request->pickup_point_id);
        $pickup_point->pickuppoint_name = $request->pickup_point_name;
        if ($pickup_point->save()) {
            $response['status'] = true;
            $response['msg'] = 'Pickup Point Updated!';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Pickup Point could not be updated!';
        }

        return json_encode($response);
    }
}
