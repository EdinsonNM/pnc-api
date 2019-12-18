<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportExcel implements FromView
{
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    public function view(): View
    {
        return view('downloadTable', [
            'data'=>$this->data
        ]);
    }
}
