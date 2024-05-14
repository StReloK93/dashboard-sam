<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ReportExport implements FromCollection, WithHeadings
{

   protected $date;
   public function __construct($date)
   {

      $this->date = Carbon::parse($date)
         ->timezone('Asia/Tashkent')
         ->startOfDay()
         ->addHours(9)
         ->addMinutes(10);
   }

   public function collection()
   {
      $arr = DB::select("SELECT * FROM [dbo].[FMTBF_MTTR] (?, 1, 7)
        order by hafta, [name]", [$this->date]);

      return collect($arr);
   }

   public function headings(): array
   {
      return [

      ];
   }


}


// SELECT * FROM [dbo].[FMTBF_MTTR] ('2024-05-09 09:10', 12, 7)
// order by [name]