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

    public $phone;
    /**
     * Create a new job instance.
     */
    public function __construct($blackList,$note, $userId, $phone )
    {
        $this->blackList = $blackList;
        $this->note = $note;
        $this->userId = $userId;
        $this->phone = $phone;
        

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        // TODO lograr hacer todo con una sola consulta

        $phonesOnDb = DB::table('phone_numbers')->select('id', 'rut','area', 'number')->get()->keyBy('number')->toArray();       
        $phonesOnDbWithCode = DB::table('phone_numbers')->select('id', 'rut','area', 'number', DB::raw("CONCAT(area, number) as full_phone"))->get()->keyBy('full_phone')->toArray();



        $newPhones = [];


        Log::info('Empezando');

        $compareWithoutCode = array_intersect_key($this->blackList, $phonesOnDb);
        $compareWithCode = array_intersect_key($this->blackList,$phonesOnDbWithCode);
        

        // dump( $compareWithCode );
        // dump($compareWithoutCode);

        // dump('encontrados con codigo');
        foreach ($compareWithCode as $key => $value) {
            // dump($key);
            // dump($value);
            // dump($phonesOnDbWithCode[$key]);
            array_push($newPhones,[$phonesOnDbWithCode[$key]->rut,$phonesOnDbWithCode[$key]->area,$phonesOnDbWithCode[$key]->number]);
        }
        // dump('encontrados sin codigo');
        foreach ($compareWithoutCode as $key => $value) {
            // dump($key);
            // dump($value);
            // dump($phonesOnDb[$key]);
            array_push($newPhones,[$phonesOnDbWithCode[$key]->rut,$phonesOnDbWithCode[$key]->area,$phonesOnDbWithCode[$key]->number]);
        }
        Log::info('Coincidencias encontradas'.count($newPhones));
        Log::info('Finalizado');
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
        $this->phone->file_url = $newPhonesFilePath;
        $this->phone->file_name = explode('/',$newPhonesFilePath)[0];
        $this->phone->new_black_list = count($newPhones);
        $this->phone->status = 'completed';
        $this->phone->total_phones_processed = count($this->blackList);
        $this->phone->save();
    }
}
