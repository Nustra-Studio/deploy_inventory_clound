<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\barang;
use App\Models\category_barang;
use App\Models\suplier;
use App\Models\singkron;
class BarangImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $data = $collection->slice(1);
        foreach($data as $item){
            $uuid = hash('sha256', uniqid(mt_rand(), true));
            $category = category_barang::where('name',$item[7])->first();
            $suplier = suplier::where('nama','non')->value('uuid');
            $barang = barang::where('kode_barang',$item[1])->first();
            $id_category = '';
            if(empty($category)){
                $uuid_category = hash('sha256', uniqid(mt_rand(), true));
                category_barang::insert([
                    'name'=>$item[7],
                    'uuid'=>$uuid_category
                ]);
                $singkron =  [
                    'name'=>'categorybarang',
                    'status'=>'create',
                    'uuid'=>$uuid_category,
                ];
                singkron::insert($singkron);
                $id_category = $uuid_category;
            }
            else{
                $id_category = $category->uuid;
            }
            if(empty($item[4])){
                $stock = 0;
            }
            else{
                $stock=$item[4];
            }
            $data = [
                'uuid'=>$uuid,
                'name'=>$item[2],
                'id_supplier'=>$suplier,
                'category_id'=>$id_category,
                'kode_barang'=>$item[1],
                'harga_jual'=>$item[6],
                'harga_pokok'=>$item[3],
                'stok'=>$stock
            ];
            if(empty($barang)){
                barang::create($data);
            }
            else{
                $barang->update($data);
            }
        }

    }
}
