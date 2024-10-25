<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
class PhoneController extends Controller
{
    public function store(Request $request): RedirectResponse
    {


        // Store the file in storage\app\public folder
        $file = $request->file('file_upload');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('uploads', 'public');


        return redirect(route('phones.index'));
    }
    public function index(): View
    {
        return view('phones.index');
    }
}
