<?php

namespace App\Http\Controllers;
use App\Imports\PhoneNumberImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;


class PhoneNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $phoneNumbers = PhoneNumber::paginate(10);
        return view("phones-database.index", compact('phoneNumbers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("phones-database.import");
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

        $user = Auth::user();

        $request->validate([
            'file_upload' => [
                'required',
                'file'
            ],
        ]);

        ini_set('max_execution_time',800); 

            $importer =new PhoneNumberImport($user);
            $import = Excel::import($importer, $request->file('file_upload'));
                        
            Log::warning('Errores '. json_encode( $importer->errors()) );    
            
            // return response()->json(['success' => 'You have successfully upload file.']);
            return view("phones-database.index");
    }
}
