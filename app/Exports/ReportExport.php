<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

use Carbon\Carbon;
use DB;
class ReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{

   protected $date;
   protected $weekCount;
   public function __construct($date, $weekCount)
   {
      $this->weekCount = $weekCount;
      $this->date = Carbon::parse($date)
         ->timezone('Asia/Tashkent')
         ->startOfDay()
         ->addHours(9)
         ->addMinutes(10);
   }

   public function collection()
   {
      $arr = DB::select("SELECT * FROM [dbo].[FMTBF_MTTR] (?, ?, 7)
        order by [name], [hafta]", [$this->date, $this->weekCount]);

      return collect($arr);
   }

   public function headings(): array
   {
      // Метод headings остается таким же
      return [
         'Transport',
         'Hafta',
         'Umumiy soat',
         'Ishladi (soat)',
         'Umumiy tamirlashda (soat)',
         'Umumiy tamirlashda soni',
         'Mayda tamirlashda (soat)',
         'Mayda tamirlashda soni',
         'ATBda (soat)',
         'MTBF',
         'MTBR',
      ];
   }


   public function map($row): array
   {
      return [
         $row->name,
         $row->hafta,
         $row->kv,
         $row->kg,
         $row->vr,
         $row->kol_p,
         $row->sum_vr_p,
         $row->kol_uat,
         $row->sum_vr_uat,
         $row->mtbf,
         $row->mtbr,
      ];
   }
}