<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection,withHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $exceldata ;

    public function __construct($exceldata)
    {
        $this->exceldata = $exceldata;
    }

    public function collection()
    {
        // $array = [];
        // $count = 0;
        // foreach($this->txtresults as $key => $data)
        // {
        //     $count = $key+1;
        //    $mainarray = [
        //     'sr_no' => $count,
        //     'Vin' => $data['vin'],
        //     'Reg No.' => $data['regNo'],
        //     'UploadDate' => $data['uploadDate'],
        //     'Dents' => $data['dents'],
        //     'afterRepair' => $data['afterRepair']
        //    ];
        //    array_push($array , $mainarray);

        // }
        // return collect($this->array);
        // $count = 0;
        // foreach($this->txtresults as $key => $data)
        // {
        //     $count = $key+1;
        //    \Log::info($key);
        //    $this->txtresults[$key]['sr_no'] = $count;

        // }
        //     // \Log::info($this->txtresults);
        return collect($this->exceldata);

        // return User::all();
    }
    public function headings(): array
    {
        return [
            'No',
            'VIN',
            'REG NO',
            'UPLOADDATE',
            'DENTS',
            'MODIFIEDDATE',
            'Scan Of Count',
        ];
    }
}
