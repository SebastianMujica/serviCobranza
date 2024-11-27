<?php

namespace App\Imports;

use App\Models\PhoneNumber;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Events\ImportFailed;
use App\Models\User;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;



class PhoneNumberImport implements ToModel, WithChunkReading, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;
    protected $importedBy;
    private $actualRow=0;
    public function __construct(User $importedBy)
    {
        $this->importedBy = $importedBy;
    }
    public function model(array $row)
    {   
        $this->actualRow++;
        if ($row['rut'] != '' || $row['dv'] != '' || $row['area'] != '' || $row['number'] != ''){
            return new PhoneNumber([
                "rut" => $row['rut'],
                "dv" => $row['dv'],
                "area" => $row['area'],
                "number"=> $row['number'],
            ]);
        }else{

            Log::warning('Row is empty'. json_encode($row) );
            return null;
        }
    }
    public function chunkSize(): int
    {
        return 1000;
    }
    public function onError(\Throwable $e)
    {   
        Log::warning('Error con el registro '.$this->actualRow.' '. json_encode( $e) );
    }
    public function rules(): array
    {
        return [];
    }
}