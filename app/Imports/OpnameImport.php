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
        $data = $collection->get('Sheet1');
        foreach($data as $item){
            $id_toko = $item[3];
            $uuid = $item[0];
            $barcode = $item[1];
            $stock = $item[2];
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
