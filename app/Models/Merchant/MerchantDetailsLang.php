<?php

namespace App\Models\Merchant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Merchant\MerchantDetailsLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang whereParentId($value)
 * @property string|null $offer_details
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetailsLang whereOfferDetails($value)
 * @mixin \Eloquent
 */
class MerchantDetailsLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "merchant_details_lang";
    protected $hidden = ['id', 'parent_id'];

    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}