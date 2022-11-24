<?php

namespace App\Exports;
use App\Models\TxtResult;
use App\Models\CustomerInformation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VinExport implements FromCollection,WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $name;
    function __construct($data) {
      \Log::info($data);
       $this->name = $data; 
    }

    public function collection()
    {
      \Log::info("sssss");
      \Log::info($this->name);
       return collect($this->name);
    }

    public function headings(): array
    {
        return [
            'VIN',
            'actualVIN',
            'Location',
            'OrderNo',
            'UploadDate',
            'CustomerName',
            'Make',
            'Model',
            'Color',
            'TotalPrice',
            'TotalDents',
            'ImageCount',
        ];
    }
}
