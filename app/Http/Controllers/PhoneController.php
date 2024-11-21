<?php

namespace App\Http\Controllers;

use App\Imports\PhoneNumberImport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Phone;
use Illuminate\Support\Facades\Storage;
use App\Exports\PhonesExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProccessPhones;
use Illuminate\Support\Facades\Log;

class PhoneController extends Controller
{   


    public function store(Request $request): RedirectResponse
    {
        ini_set('max_execution_time',800); 
        $blackList = [];

        $validated = $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $file = $request->file('file_upload');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('imported', 'public');
        $extension = $file->getClientOriginalExtension();

        if ($extension == 'txt') {  

            $contents = file($file->getRealPath());

            for ($i=0; $i < count($contents); $i++) { 
                $line = $contents[$i];
                $line = trim($line);
                    if (preg_match('/^\d{7,8}_\d{9}$/', $line)) {
                        $rutNumberArray= explode('_',$line);
                        $numberComplete= $rutNumberArray[1];

                        if (preg_match('/^(9|75|73|72|71|67|65|64|63|61|58|57|55|53|52|51|43|42|41|34|32|2)(\d{8})$/', $numberComplete, $matches)) {
                            $area = $matches[1];
                            $numero = $matches[2];                    
                        }

                        array_push($blackList, explode('_',$line)[1]);

                        $blackList[$i]=['rut'=>$rutNumberArray[0] ,'area'=>$area,'number'=>$numero];
                        Log::debug('Registro valido '.$blackList[$i]['rut'].' '.$blackList[$i]['area'].' '.$blackList[$i]['number'] );
                    }else{
                        Log::info('Registro invalido '.$line);
                    }   
            }
            
            Log::info(message: count($blackList));
            
            }else{
                $data = Excel::toArray(new PhoneNumberImport,$filePath);
                foreach ($data as $sheetNumber => $sheetData) {
                    foreach ($sheetData as $row => $value) {
                        if (preg_match('/^\d{7,8}_\d{9}$/', $value[0])) {

                            array_push($blackList, explode('_',$value[0])[1]);
                        }
                    }
                }
            }

        

        ProccessPhones::dispatch($blackList,  $request->input('note'), auth()->id())->afterCommit();

        // $batch = Bus::batch([])->dispatch()->afterCommit();
        
        // $batch->add(new ProccessPhones(       $blackList,  $request->input('note'), auth()->id()));    
        

        // $id = $batch->id;
        
        return redirect()->route('phones.index');
    }
    public function index(): View
    {
        return view('phones.index', [
            'phones' => Phone::with('user')->latest()->get(),
        ]);
    }

    public function destroy(Phone $phone): RedirectResponse
    {
        
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
    public function batch($id) {
        $batchId = $id;
        $data = Bus::findBatch($batchId);
        $data1 = json_encode($data, true);
        $data2 = json_decode($data1, true);
        $progress = $data2['progress'];
        $createdAt = date('F j, Y, g:i A' , strtotime($data2['createdAt']));
        $finishedAt = !empty($data2['finishedAt']) ? date('F j, Y, g:i A' , strtotime($data2['finishedAt'])) : null;
        return response()->json(['progress' => $progress , 'createdAt' => $createdAt , 'finishedAt' => $finishedAt]);
    }
}
