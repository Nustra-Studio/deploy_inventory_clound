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
        $id_toko = $request->input('id_toko');
        $data = $request->data;
        $uuid = $data['uuid'];
        $barcode = $data['barcode'];
        $stock = $data['stock'];
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
