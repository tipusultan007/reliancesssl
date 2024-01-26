<?php

namespace App\Http\Controllers;

use App\Models\Nominee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NomineeController extends Controller
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
        $nominee = Nominee::find($id);
        $data = $request->all();
        if ($request->hasFile('nominee_nid')) {
            $oldFilePath = $nominee->nominee_nid;
            $imagePath = upload_image($request->file('nominee_nid'), 'nominee_nid', $oldFilePath);
            $data['nominee_nid'] = $imagePath;
        }else{
            unset($data['nominee_nid']);
        }
        if ($request->hasFile('nominee_nid1')) {

            $oldFilePath = $nominee->nominee_nid1;
            $imagePath = upload_image($request->file('nominee_nid1'), 'nominee_nid', $oldFilePath);
            $data['nominee_nid1'] = $imagePath;

        }else{
            unset($data['nominee_nid1']);
        }
        if ($request->hasFile('nominee_photo')) {
            $oldFilePath = $nominee->nominee_photo;
            $imagePath = upload_image($request->file('nominee_photo'), 'nominee_photo', $oldFilePath);
            $data['nominee_photo'] = $imagePath;
        }else{
            unset($data['nominee_photo']);
        }
        if ($request->hasFile('nominee_photo1')) {
            $oldFilePath = $nominee->nominee_photo1;
            $imagePath = upload_image($request->file('nominee_photo1'), 'nominee_photo', $oldFilePath);
            $data['nominee_photo1'] = $imagePath;
        }else{
            unset($data['nominee_photo1']);
        }

        $nominee->update($data);
        return redirect()->back();
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
