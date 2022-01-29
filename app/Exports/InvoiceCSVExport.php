<?php

namespace App\Exports;

use App\Models\DATA\INVOICE\data_invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Controllers\API\DATA\Data_Controller;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use App\Http\Controllers\API\AllUsedFunction;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use \Maatwebsite\Excel\Excel;
use App\Http\Controllers\API\DATA\INVOICE\InvoiceDataController;

// class ShipmentCSVExport implements FromQuery,WithHeadings,WithMapping,ShouldAutoSize
class InvoiceCSVExport extends DefaultValueBinder implements WithStrictNullComparison, FromQuery,WithHeadings,ShouldAutoSize,WithColumnFormatting
{
    use Exportable;
    private $request;
    private $all_functions;
    public function __construct($request)
    {
        $this->request = $request;
        $this->all_functions = new AllUsedFunction();
    }

    private $writerType = Excel::CSV;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];
    public function headings(): array
    {
        return InvoiceDataController::invoiceCsvHeading();
    }
    public function query()
    {
        $invoice_csv_data = InvoiceDataController::get_invoice_data($this->request);
        return $invoice_csv_data;
    }
    public function columnFormats(): array
    {
        return [
            'CS' => '0.0',
            'DA' => '@',
            'DA' => '0.000',
            'EW' => '0.00',
            'FD' => '0.0',
            'FF' => '0.0',
            'FH' => '0.0',
            'FK' => '0.000',
            'FM' => '0.000',
            'FN' => '0.000',
            'FO' => '0.000',
        ];
    }

}

