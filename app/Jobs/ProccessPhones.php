<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PhoneNumber;
use App\Imports\PhoneNumberImport;
use App\Models\Phone;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ProccessPhones implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $blackList;
    public $note;
    public $userId;

    public $useSql;
    /**
     * Create a new job instance.
     */
    public function __construct($blackList,$note, $userId, $useSql = true )
    {
        $this->blackList = $blackList;
        $this->note = $note;
        $this->userId = $userId;
        $this->useSql = $useSql;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        

        // $phonesOnDb = PhoneNumber::select('area','number')->get();  
        // $phonesIndex = PhoneNumber::pluck('id', 'number');

        $phonesOnDb = DB::table('phone_numbers')->select('id', 'rut','area', 'number')->get()->keyBy('number')->toArray();
        
        $phonesOnDbWithCode = DB::table('phone_numbers')->select('id', 'rut','area', 'number', DB::raw("CONCAT(area, number) as full_phone"))->get()->keyBy('full_phone')->toArray();



        $newPhones = [];


        Log::info('Empezando');
/*
        $indexPhonesOnDb = array_keys($phonesOnDb);
        $indexPhonesOnDbWithCode = array_keys($phonesOnDbWithCode);
        $indexBlacklist = array_keys($this->blackList);
*/
        $compareWithoutCode = array_intersect_key($this->blackList, $phonesOnDb);
        $compareWithCode = array_intersect_key($this->blackList,$phonesOnDbWithCode);
        

        dump( $compareWithCode );
        dump($compareWithoutCode);

        dump('encontrados con codigo');
        foreach ($compareWithCode as $key => $value) {
            dump($key);
            dump($value);
            dump($phonesOnDbWithCode[$key]);
            array_push($newPhones,[$phonesOnDbWithCode[$key]->rut,$phonesOnDbWithCode[$key]->area,$phonesOnDbWithCode[$key]->number]);
        }
        dump('encontrados sin codigo');
        foreach ($compareWithoutCode as $key => $value) {
            dump($key);
            dump($value);
            dump($phonesOnDb[$key]);
            array_push($newPhones,[$phonesOnDbWithCode[$key]->rut,$phonesOnDbWithCode[$key]->area,$phonesOnDbWithCode[$key]->number]);
        }

        /*

        for ($i=0; $i < count($this->blackList)-1; $i++) { 
            
            if (isset($this->blackList[$i])){

                Log::debug(json_encode($this->blackList[$i]));

                $aguja = $this->blackList[$i];
                
                if ($this->useSql == true){
                    Log::debug('usando sql');

                    $sql = "SELECT * FROM phone_numbers where number = $aguja or concat(area,number) = $aguja ;";
                    $check = DB::select($sql);
                    Log::debug($sql);

                    if (count($check)>0){
                        $newPhones[$i] = $this->blackList[$i];
                    }

                }else{
                    if (!in_array($aguja, $phonesOnDbWithArea)) { 
                        $newPhones[$i] = $this->blackList[$i];
                    }
                }
            }
        }*/
        // Create a new Excel file with the newPhones array
        $export = new class($newPhones) implements FromCollection, WithHeadings {
            protected $newPhonesExp;

            public function __construct($newPhones)
            {
                $this->newPhonesExp = $newPhones;
            }

            public function collection()
            {
                return collect($this->newPhonesExp);
            }
            public function headings(): array
            {
                return [
                    'rut',
                    'codigo',
                    'numero',
                ];
            }
        };

        // Store the Excel file
        $newPhonesFilePath = 'new_phones_' . time() . '.xlsx';
        
        Excel::store($export, $newPhonesFilePath, 'public');   
        // Return the full path to the stored file        
        $phone = Phone::create([
            'note' =>$this->note,
            'file_url' =>$newPhonesFilePath,
            'file_name' =>explode('/',$newPhonesFilePath)[0] ,
            'new_black_list'=>count($newPhones),
            'errors'=>0,
            'user_id' => $this->userId
        ]);
    }
}
