<?php

namespace App\Models\Branch;

use App\Filament\Helpers\Translatable;
use App\Helpers\ApiResponse;
use App\Helpers\HasAuthor;
use App\Helpers\HasTimestamps;
use App\Models\Merchant\Merchant;
use App\Models\Offer\Offer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * App\Models\Branch\Branch
 *
 * @property int $id
 * @property int $user_id
 * @property int $weight
 * @property int $status
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \App\Models\Branch\BranchLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branch\BranchLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|Branch active()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch translations()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch withTranslation(?string $locale = null)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereAdminId($value)
 * @property int $merchant_id
 * @property string|null $phone
 * @property float|null $longitude
 * @property float|null $latitude
 * @property-read Merchant $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch wherePhone($value)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Offer> $offers
 * @property-read int|null $offers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Branch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch withoutTrashed()
 * @property int $mall
 * @property int $avenue
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereAvenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereMall($value)
 * @property string|null $location
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLocation($value)
 * @mixin \Eloquent
 */
class Branch extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, SoftDeletes, Translatable, Auditable, \App\Helpers\HasTranslations, ApiResponse, HasTimestamps, HasAuthor;

    const MALL_TYPE = 1;
    const AVENUE_TYPE = 2;

    public $translationModel = BranchLang::class;

    public array $upload_attributes = [];

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public array $translatedAttributes = [
        'title'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public static function getTypes()
    {
        return [
            static::MALL_TYPE => __("Mall"),
            static::AVENUE_TYPE => __("Avenue"),
        ];
    }
}
