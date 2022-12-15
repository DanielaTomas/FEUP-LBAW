<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function create(){
        return view('pages.upload');
    }

    public function store(Request $request)
    {
        $size = $request->file('image')->getSize();
        $name = $request->file('image')->getClientOriginalName();

        $request->file('image')->storeAs('public/images/',$name);
        $image = new Upload();
        $image->filename = $name;
        $image->save();
        return redirect()->back();
    }
}
