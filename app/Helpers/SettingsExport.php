<?php

namespace App\Helpers;

use App\Models\AnnualReport;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SettingsExport extends ExcelExport
{
    public function setModel($model){
        $this->model = $model;
        return $this;
    }
}

