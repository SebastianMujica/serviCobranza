<?php

namespace App\Exports;

use App\Models\Phone;
use Maatwebsite\Excel\Concerns\FromCollection;

class PhonesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Phone::all();
    }
}
