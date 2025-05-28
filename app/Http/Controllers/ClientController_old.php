<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $all_clients = Client::all();
        // return $all_clients;
        return view('clients.index', compact('all_clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        if ($request->hasfile('client_img')) {
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_client = $time . '-' . $request->name . '-' . $request->client_img->getClientOriginalName();
            $request->client_img->move(public_path('client_images'), $image_client);
            Client::create([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'client_img' => $image_client,

                'email1' => $request->email1,
                'email2' => $request->email2,
                'segl_togary' => $request->segl_togary,
                'betaa_darebia' => $request->betaa_darebia,

            ]);
        } else {
            Client::create([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'email1' => $request->email1,
                'email2' => $request->email2,
                'segl_togary' => $request->segl_togary,
                'betaa_darebia' => $request->betaa_darebia,


            ]);
        }
        session()->flash("success", "تم الاضافة بنجاح");
        return redirect()->route('client.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // return $request;
        $client=Client::where('id',$request->client_id)->first();
        if ($request->hasfile('client_img')){
            if(isset($client->client_img))
            {
             $image_path = public_path() . '/client_images' . '/' . $client->client_img;
             if(file_exists($image_path)){
                 unlink($image_path);
             }else{
                 
             }

            }
            $var = date_create();
            $time = date_format($var, 'YmdHis');
            $image_client = $time . '-' . $request->name . '-' . $request->client_img->getClientOriginalName();
            $request->client_img->move(public_path('client_images'), $image_client);
            $client->update([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'client_img' => $image_client,

                'email1' => $request->email1,
                'email2' => $request->email2,
                'segl_togary' => $request->segl_togary,
                'betaa_darebia' => $request->betaa_darebia,

            ]);
        }else{
            $client->update([
                'name' => $request->name,
                'address' => $request->address,
                'tel01' => $request->tel01,
                'tel02' => $request->tel02,
                'tel03' => $request->tel03,
                'national_no' => $request->national_no,
                'notes' => $request->notes,
                'email1' => $request->email1,
                'email2' => $request->email2,
                'segl_togary' => $request->segl_togary,
                'betaa_darebia' => $request->betaa_darebia,

            ]);

        }
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Request $request)
    {
        // return $request;
  
  
   $client=Client::where('id',$request->client_id)->first();
        // return $client;
        $invoice=Invoice::where('client_id',$request->client_id)->first();
        if(isset($invoice))
        {
            session()->flash("success", "لا يمكن حذف هذا العميل  ");
            return redirect()->route('client.index');

        }else{
            if(isset($client->client_img))
            {
             $image_path = public_path() . '/client_images' . '/' . $client->client_img;
             if(file_exists($image_path)){
                unlink($image_path);
            }else{
    
            }
    
            }
            if(isset($client->segl_togary))
            {
             $image_path = public_path() . '/segl_togary_images' . '/' . $client->segl_togary;
             if(file_exists($image_path)){
                unlink($image_path);
            }else{
    
            }
            }
            if(isset($client->betaa_darebia))
            {
                 $image_path = public_path() . '/betaa_darebia_images' . '/' . $client->betaa_darebia;
                 if(file_exists($image_path)){
                    unlink($image_path);
                }else{
    
                }
            }
            $client->delete();
            session()->flash("success", "تم الحذف بنجاح");
            return redirect()->route('client.index');

        }



    }
}
