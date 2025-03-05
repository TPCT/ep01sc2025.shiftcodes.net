<?php

namespace App\Exports;

use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Client;
use App\Models\Offer\Offer;
use App\Models\Voucher\Voucher;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct(private $id=null, private $from=null, private $to=null){
    }

    public function headings(): array
    {
        return [
            'type',
            'name [en]',
            'name [ar]',
            'details [en]',
            'details [ar]',
            'description [en]',
            'description [ar]',
//            'client rate',
//            'client comment',
            'redemption date'
        ];
    }

    public function map($row): array
    {
        $model = $row->redeemable_type::find($row->redeemable_id);
        $row = $row->toArray();
        $row = \Arr::except($row, ['id', 'redeemable_type', 'redeemable_id', 'merchant_id', 'client_id', 'redeem_token']);

        $model_data = [
            \Str::afterLast($model::class, '\\')
        ];

        if (in_array($model::class, [Voucher::class, BoothVoucher::class])){
            $model_data[] = $model->translate('en')->title;
            $model_data[] = $model->translate('ar')->title;
            $model_data[] = $model->translate('en')->discount;
            $model_data[] = $model->translate('ar')->discount;
            $model_data[] = '---------';
            $model_data[] = '---------';
        }else{
            $model_data[] = $model->translate('en')->title;
            $model_data[] = $model->translate('ar')->title;
            $model_data[] = $model->translate('en')->details;
            $model_data[] = $model->translate('ar')->details;
            $model_data[] = $model->translate('en')->description;
            $model_data[] = $model->translate('ar')->description;
        }

//        $model_data[] = $row['redeem_rate'];
//        $model_data[] = $row['redeem_comment'];
        $model_data[] = $row['redeemed_at'];

        return $model_data;
    }

    public function query(){
        $merchant = auth()->user();
        if ($this->id != null){
            return $merchant
                ->redeemable()
                ->where(function ($query){
                    $query->where('redeemable_id', $this->id);
                    $query->where('redeemable_type', Offer::class);
                    $query->whereNotNull('redeemed_at');
                });
        }
        return $merchant->redeemable()->where(function ($query){
            $query->whereNotNull('redeemed_at');
            $query->when($this->from, function ($query){
                return $query->whereDate('redeemed_at', '>=', $this->from);
            });
            $query->when($this->to, function ($query){
               return $query->whereDate('redeemed_at', '<=', $this->to);
            });
        });
    }
}