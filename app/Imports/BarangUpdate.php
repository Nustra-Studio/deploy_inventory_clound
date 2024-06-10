<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\barang;
use App\Models\category_barang;
use App\Models\suplier;
use App\Models\singkron;
use Illuminate\Support\Facades\DB;
class BarangUpdate implements ToCollection , WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    private $startColumn;
    private $endColumn;

    public function __construct($startColumn, $endColumn)
    {
        $this->startColumn = $startColumn;
        $this->endColumn = $endColumn;
    }
    public function collection(Collection $collection)
    {
        
        if($this->startColumn <= 0 || empty($this->startColumn)){
            $data = $collection->slice(3);
        }
        else{
            $startColumnn =(int)$this->startColumn - 1;
            $endColumn = (int) $this->endColumn -1 ;
            $data = $collection->slice($startColumnn,$endColumn);
        }
        // dd($this->endColumn);
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
                            'product' => 'non',
                            'keterangan' => 'singkron',
                            'alamat' => 'empty',
                            'telepon' => "0",
                            'category_barang_id' => $category,
                            'uuid' => $uuid_supplier,
                            'status'=>'non'
                        ];
                        $singkron = [
                            'name' => 'supplier',
                            'status' => 'create',
                            'uuid' => $uuid_supplier,
                        ];
                        
                        DB::table('supliers')->insert($data);
                        singkron::create($singkron);
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
                    $stock_update = intval($item[3]);
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
                    $singkron = [
                        'name' => 'barang',
                        'status' => 'update',
                        'uuid' => $uuid,
                    ];
                    singkron::create($singkron);
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
                    $singkron = [
                        'name' => 'barang',
                        'status' => 'create',
                        'uuid' => $uuid,
                    ];
                    singkron::create($singkron);
                }
            }
        }
        // dd($items);
    }

}

