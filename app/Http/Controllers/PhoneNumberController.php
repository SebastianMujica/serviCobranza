<?php

namespace App\Http\Controllers;
use App\Imports\PhoneNumberImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;


class PhoneNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view("phones-database.index");
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhoneNumber $phoneNumber)
    {
        //
    }
    public function import(Request $request) 
    {
        $request->validate([
            'file_upload' => [
                'required',
                'file'
            ],
        ]);
        
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        Excel::import(new PhoneNumberImport(), $request->file('file_upload'));
        return redirect()->back()->with('status', 'import-success');
    }
}
