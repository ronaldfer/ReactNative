<?php

namespace App\Exports;

use App\Models\PalletsFileData;
use Maatwebsite\Excel\Concerns\FromCollection;

class PalletsFileDataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PalletsFileData::all();
    }
}
