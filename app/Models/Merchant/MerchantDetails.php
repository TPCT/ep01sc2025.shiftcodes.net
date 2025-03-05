<?php

namespace App\Models\Merchant;

use App\Filament\Helpers\Translatable;
use App\Helpers\ApiResponse;
use App\Models\Faq\FaqLang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Merchant\MerchantDetails
 *
 * @property int $id
 * @property int $merchant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $image_id
 * @property int|null $cover_image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Merchant\Merchant|null $merchant
 * @property-read \App\Models\Merchant\MerchantDetailsLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Merchant\MerchantDetailsLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails translated()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereCoverImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantDetails withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
class MerchantDetails extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, \App\Helpers\HasTranslations, Translatable, ApiResponse;


    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $translationModel = MerchantDetailsLang::class;

    public array $translatedAttributes = [
        'name', 'offer_details'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }
}
