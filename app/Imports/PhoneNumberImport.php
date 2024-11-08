<?php

namespace App\Imports;

use App\Models\PhoneNumber;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PhoneNumberImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            PhoneNumber::create([
                "rut" => $row[0],
                "dv" => $row[1],
                "area" => $row[2],
                "number"=> $row[3],
            ]);
        }
    }
}