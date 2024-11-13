<?php

namespace App\Http\Controllers;

use App\Imports\PhoneNumberImport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Phone;
use Illuminate\Support\Facades\Storage;
use App\Exports\PhonesExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Models\PhoneNumber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PhoneController extends Controller
{
    public function store(Request $request): RedirectResponse
    {

        $blackList = [];

        $file = $request->file('file_upload');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('imported', 'public');


        $validated = $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $data = Excel::toArray(new PhoneNumberImport,$filePath);

        foreach ($data as $sheetNumber => $sheetData) {
            foreach ($sheetData as $row => $value) {
             if (preg_match("/^\d{8}_\d{9}$/", $value[0])) {
                $reg = [];
                array_push($blackList, explode('_',$value[0])[1]);
                }
            }
        }


        $phonesOnDb = PhoneNumber::all()->pluck('number')->toArray();

        $newPhones = [];

        foreach($blackList as $key => $value) {
            if (in_array($value, $phonesOnDb)) {          
            }else{
                array_push($newPhones, $value);
            }
        }

        // Create a new Excel file with the newPhones array
        $export = new class($newPhones) implements FromCollection {
            protected $newPhones;

            public function __construct($newPhones)
            {
                $this->newPhones = $newPhones;
            }

            public function collection()
            {
                return collect($this->newPhones)->map(function ($phone) {
                    return ['phone_number' => $phone];
                });
            }
        };

        // Store the Excel file
        $newPhonesFilePath = 'new_phones_' . time() . '.xlsx';
        
        Excel::store($export, $newPhonesFilePath, 'public');
        
        // Return the full path to the stored file        
        $phone = Phone::create([
            'note' => $request->input('note'),
            'file_url' =>$newPhonesFilePath,
            'file_name' =>explode('/',$newPhonesFilePath)[0] ,
            'new_black_list'=>count($newPhones),
            'errors'=>0,
            'user_id' => auth()->id()
        ]);

        return redirect(route('phones.index'));
    }
    public function index(): View
    {
        return view('phones.index', [
            'phones' => Phone::with('user')->latest()->get(),
        ]);
    }

    public function destroy(Phone $phone): RedirectResponse
    {
        //
        Storage::delete($phone->file_url);

        $phone->delete();
 
        return redirect(route('phones.index'));
    }
    /**
     * @return BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new PhonesExport, 'phones.xlsx');
    }
    
    public function download($file_name){
        if (Storage::exists($file_name)) {         
            return Storage::download( $file_name);
        }
        return response('',404);
    }
}
