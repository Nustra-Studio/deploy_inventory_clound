<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\barang;
use App\Models\category_barang;
use App\Models\suplier;
use App\Models\singkron;
class BarangUpdate implements ToCollection , WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $data = $collection->slice(3);
        foreach($data as $item){
            if(!empty($item[0])||!empty($item[2])){
                $uuid = hash('sha256', uniqid(mt_rand(), true));
                // check huruf
                preg_match_all('/[a-zA-Z]+/', $item[0], $textMatches);
                $name_category = implode('', $textMatches[0]);
                // check category
                $categorys= category_barang::where('name',$name_category)->first();
                if(!empty($categorys)){
                    $category = $categorys->uuid;
                }
                else{
                    $uuid_category = hash('sha256', uniqid(mt_rand(), true));
                        $data_category = category_barang::insert([
                        'name'=>$name_category,
                        'uuid'=>$uuid_category
                    ]);
                    $category = $uuid_category;
                }
                // end check category
                if (!empty($item[11])) {
                    $name_supplier = suplier::where('nama', $item[11])->first();
                    if (!empty($name_supplier)) {
                        $supplier = $name_supplier->uuid;
                    } else {
                        $uuid_supplier = hash('sha256', uniqid(mt_rand(), true));
                        $data = [
                            'nama' => $item[11],
                            'product' => 'default',
                            'keterangan' => 'singkron',
                            'alamat' => 'empty',
                            'telepon' => "0",
                            'category_barang_id' => $category,
                            'uuid' => $uuid_supplier,
                        ];
                        $singkron = [
                            'name' => 'supplier',
                            'status' => 'create',
                            'uuid' => $uuid_supplier,
                        ];
                        suplier::insert($data);
                        singkron::insert($singkron);
                        $supplier = $uuid_supplier;
                    }
                } else {
                    $supplier = suplier::where('nama','non')->value('uuid'); // or set a default value if needed
                }
                // end check supplier
                // check update or create
                    $unit = barang::where('kode_barang',$item[0])->first();
                // end check
                if(!empty($unit)){
                    $stock_update = intval($unit->stok) + intval($item[3]);
                // action update
                    $unit->update(
                        [
                            'uuid'=>$uuid,
                            'name'=>$item[2],
                            'id_supplier'=>$supplier,
                            'category_id'=>$category,
                            'kode_barang'=>$item[0] ,
                            'harga_jual'=>$item[10] ,
                            'harga_pokok'=>$item[7],
                            'stok'=>$stock_update
                        ]
                    );
                }
                // action create
                else{
                    barang::create(
                        [
                            'uuid'=>$uuid,
                            'name'=>$item[2] ,
                            'id_supplier'=>$supplier,
                            'category_id'=>$category,
                            'kode_barang'=>$item[0] ,
                            'harga_jual'=>$item[10] ,
                            'harga_pokok'=>$item[7] ,
                            'stok'=>$item[3] 
                        ]
                    );
                }
            }
        }
        // dd($items);
    }

}

