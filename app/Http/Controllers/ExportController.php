<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
class ExportController extends Controller
{
    public function exportReport($date, $weekCount)
    {
        return Excel::download(new ReportExport($date, $weekCount), "$date-$weekCount.xlsx");
    }



    public function exportTablePdf(Request $request)
    {
        // Создаем PDF
        $html = "<html>
		<head>
			<style>
				html{
					width: 100%;
					height: 100%;
					padding: 0;
					margin: 0;
					background: rgb(21 21 26);
				}
				.w-full {
					width: 100%;
				}
				body {
					margin: 0;
					padding: 0;
					background: rgb(21 21 26);
				}
				.leading-3{
					background: rgb(21 21 26);
				}
				.content-center {
					align-content: center;
				}
				.text-center {
				text-align: center;
				}
				.h-9{
					height: 36px;
				}
				.h-10{
					height: 21px;
				}
				.max-w-20{
					max-width: 80px;
				}
				.w-10{
					width: 21px;
				}
				.border-x-2.border-zinc-900{
					padding: 0px 5px;
					font-size: 13px;
				}
				.bg-green-900.font-semibold, .bg-red-900.font-semibold, .bg-zinc-900.font-semibold{
					color: white;
					border-radius: 0.125rem;
					margin-right: 0.25rem;
					padding-left: 0.375rem;
    				padding-right: 0.375rem;
					padding-top: 0.12rem;
    				padding-bottom: 0.12rem;
					max-width: 135px;
					display: inline-block;
					align-content: start;
					font-size: 13px;
				}
				.bg-zinc-900{
					background-color: rgb(25 25 28);
				}
				.bg-green-900{
					background-color: rgb(19 76 42);
				}
				.bg-red-900{
					background-color: rgb(126 29 29);
				}
				.bg-zinc-700{
					background-color: rgb(73 73 80);
				}
				.bg-stone-950{
					background: black;
					color: white;
					width: 135px;
					height: 46px;
					text-align: center;
				}

				.bg-zinc-800{
					background: rgb(41 41 44);
				}

			</style>
		</head>
		<body>
			$request->html
		</body>
		</html>";

        $pdf = PDF::loadHTML($html)->setOptions([
            'dpi' => 150, // DPI (dots per inch) настройка для более четкого текста
            'defaultFont' => 'sans-serif', // Шрифт по умолчанию
            'isPhpEnabled' => true // Разрешение выполнения PHP кода в HTML
        ])->setPaper('A4', 'landscape')->setOptions([
                    'margin-top' => 0,
                    'margin-bottom' => 0,
                    'margin-left' => 0,
                    'margin-right' => 0,
                ]);

        // Возвращаем PDF файл для скачивания
        return $pdf->download('exported.pdf');


    }

}
