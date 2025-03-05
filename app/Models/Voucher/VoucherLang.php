<?php

namespace App\Models\Voucher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\News\NewsLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $description
 * @property string|null $title
 * @property string|null $content
 * @property int|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereTitle($value)
 * @property string $discount
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherLang whereDiscount($value)
 * @mixin \Eloquent
 */
class VoucherLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "vouchers_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
