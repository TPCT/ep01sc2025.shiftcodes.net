<?php

namespace App\Models\BoothVoucher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\BoothVoucher\BoothVoucherLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string $title
 * @property string $discount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucherLang whereTitle($value)
 * @mixin \Eloquent
 */
class BoothVoucherLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "booth_vouchers_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
