<?php

namespace App\Http\Controllers\Products;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Jobs\ProductImportJob;

class ProductImport implements ToCollection
{
    private $totaldata = 0;
    private $request = null;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $key => $val) {

            if (!empty($val)) {

                if ($key > 0) {

                    ++$this->totaldata;

                    if ((isset($val[0]) && !empty($val[0])) && (isset($val[1]) && !empty($val[1])) && (isset($val[2]) && !empty($val[2])) && (isset($val[3]) && !empty($val[3]))) {

                        $name = $price = $upc = $status = null;

                        $name = trim($val[0]);
                        $price = trim($val[1]);
                        $upc = trim($val[2]);
                        $status = trim($val[3]);
                        if($status=="Published") {
                            $status_final = 1;
                        } elseif($status=="Unpublished") {
                            $status_final = 0;
                        } else {
                            $status_final = 0;
                        }

                        $update_or_create_where = array(
                            'upc'=>$upc
                        );
                        $update_or_create = array(
                            'name'=>$name,
                            'price'=>$price,
                            'upc'=>$upc,
                            'status'=>$status_final,
                        );

                        $data = array(
                            'update_or_create_where'=>$update_or_create_where,
                            'update_or_create'=>$update_or_create
                        );

                        $importJob = (new ProductImportJob($data));
                        dispatch($importJob);

                    }
                }
            }
        }
    }

    public function getRowCount()
    {
        return array($this->totaldata);
    }

}
