<?php

namespace App\Imports;

use App\Models\Part;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportParts implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Part([
            //
            'part' => $row[0],
            'source' => $row[1],
            'status' => $row[2],
            'quality' => $row[3],
            'amount' => $row[4],
            'price' => $row[5],

        ]);
    }
}
