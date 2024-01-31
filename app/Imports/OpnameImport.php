<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\opname;
class OpnameImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $item){
            $id_toko = $item[4];
            $uuid = $item[1];
            $barcode = $item[2];
            $stock = $item[3];
            opname::create([
                'id_toko'=>$id_toko,
                'uuid'=>$uuid,
                'barcode'=>$barcode,
                'perubahan'=>'',
                'stock'=>$stock,
                'status'=>'old',
            ]);
        }
    }
}
