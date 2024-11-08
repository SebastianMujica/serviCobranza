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

class PhoneController extends Controller
{
    public function store(Request $request): RedirectResponse
    {

        $blackList = [];
        // // Store the file in storage\app\public folder
         $file = $request->file('file_upload');
         $fileName = $file->getClientOriginalName();
         $filePath = $file->store('uploads', 'public');

        // $validated = $request->validate([
        //     'message' => 'required|string|max:255',
        // ]);

        // $request->user()->phones()->create($validated);

        $validated = $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $data = Excel::toArray(new PhoneNumberImport,$filePath);

        foreach ($data as $sheetNumber => $sheetData) {
            foreach ($sheetData as $row => $value) {
             if (preg_match("/^\d{8}_\d{9}$/", $value[0])) {
                $reg = [];
                array_push($blackList, explode('_',$value[0]));
                }
            }
        }

        
        $phonesOnDb = PhoneNumber::all();

        dd($phonesOnDb);

        $phone = Phone::create([
            'note' => $request->input('note'),
            'file_url' =>$filePath,
            'file_name' => $fileName,
            'new_black_list'=>0,
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
}
