<?php

namespace App\Http\Controllers\Merchant;

use App\Exports\ReportExport;
use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\Offer\Offer;
use App\Settings\Site;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Exception;

class ReportsController extends Controller
{
    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export($locale, $report=null){
        $merchant = auth()->user();
        $data = request()->only('from', 'to', 'today', 'month', 'all');
        $data['from'] ??= null;
        $data['to'] ??= null;
        if (isset($data['today'])) {
            $data['from'] = Carbon::today()->toDateString();
            $data['to'] = Carbon::today()->toDateString();
        }else if(isset($data['month'])){
            $data['from'] = Carbon::today()->startOfMonth()->toDateString();
            $data['to'] = Carbon::today()->toDateString();
        }elseif(isset($data['all'])){
            $data['from'] = null;
            $data['to'] = Carbon::today()->toDateString();
        }
        $path = 'reports/' . $merchant->id . '/' . 'EASY-PEASY-Export-' . str(now()->toDateString()) . '.xlsx';
        $export = new ReportExport($report, $data['from'], $data['to']);
        if (!$export->query()->count())
            return Responses::error([], 404, __("errors.No Reports Found"));
        \Excel::store($export, $path, 'public');
        return Responses::success(['path' => '/storage' . asset($path, true)]);
    }

    public function active()
    {
        $merchant = auth()->user();
        $offers = $merchant
            ->offers()
            ->latest()
            ->expiryDate()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function ($offer) use ($merchant) {
                $redeemed = $merchant->redeemable()
                    ->where("redeemable_type", Offer::class)
                    ->where("redeemable_id", $offer->id);
                $output = $offer->toArray();
                if (!$redeemed->exists()) {
                    $output["redeemed_daily"] = 0;
                    $output["redeemed_monthly"] = 0;
                    return $output;
                }
                $output['redeemed_daily'] = $redeemed->whereDate('redeemed_at', Carbon::today())->count();
                $output['redeemed_monthly'] = $redeemed->where('redeemed_at', '>', Carbon::today()->subMonths(1))->count();
                return $output;
            });
        return Responses::success($offers);
    }
}
