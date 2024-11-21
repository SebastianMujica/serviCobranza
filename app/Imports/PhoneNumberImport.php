<?php

namespace App\Imports;

use App\Models\PhoneNumber;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;


class PhoneNumberImport implements ToModel, WithChunkReading, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        return new PhoneNumber([
            "rut" => $row['rut'],
            "dv" => $row['dv'],
            "area" => $row['area'],
            "number"=> $row['number'],
        ]);
    }
    public function chunkSize(): int
    {
        return 1000;
    }
    public function batchSize(): int
    {
        return 1000;
    }
}