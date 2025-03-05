<?php

namespace App\Models\Offer;

use App\Filament\Helpers\Translatable;
use App\Helpers\ApiResponse;
use App\Helpers\HasExpiryDate;
use App\Helpers\HasMedia;
use App\Helpers\HasMerchant;
use App\Helpers\HasStatus;
use App\Helpers\HasTimestamps;
use App\Helpers\HasUUID;
use App\Models\Branch\Branch;
use App\Models\Client;
use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Offer\Offer
 *
 * @property int $id
 * @property string $uuid
 * @property int $merchant_id
 * @property int $branch_id
 * @property int|null $image_id
 * @property string|null $start_date
 * @property string|null $expiry_date
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Branch $branch
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read Merchant $merchant
 * @property-read \App\Models\Offer\OfferLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Offer\OfferLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Offer active()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withTranslation(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withoutTrashed()
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereDeletedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @method static \Illuminate\Database\Eloquent\Builder|Offer activeOffers()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer hasExpiryDate()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer expiryDate()
 * @property string|null $published_at
 * @method static \Illuminate\Database\Eloquent\Builder|Offer wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer hasMerchant()
 * @property int $active
 * @property int $redemption_rate
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereRedemptionRate($value)
 * @mixin \Eloquent
 */
class Offer extends Model implements Auditable
{
    use SoftDeletes, HasFactory, HasTimestamps,
        HasMerchant, \App\Helpers\HasTranslations,
        HasMedia, HasStatus, HasExpiryDate,
        \OwenIt\Auditing\Auditable, Translatable,
        HasUUID, ApiResponse;


    public $translationModel = OfferLang::class;


    protected $guarded = ['id', 'created_at', 'updated_at'];


    public array $translatedAttributes = [
        'title', 'details', 'description', 'branch'
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function clients(){
        return $this->morphToMany(Client::class, 'redeemable', 'redeemable');
    }
}
