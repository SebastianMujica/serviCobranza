<?php

namespace App\Imports;

use App\Models\PhoneNumber;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
class PhoneNumberImport implements ToModel, WithChunkReading
{
    public function model(array $row)
    {
        return new PhoneNumber([
            "rut" => $row[0],
            "dv" => $row[1],
            "area" => $row[2],
            "number"=> $row[3],
        ]);
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}