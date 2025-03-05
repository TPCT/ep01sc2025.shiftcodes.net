<?php

namespace App\Models\Voucher;

use App\Filament\Helpers\Translatable;
use App\Helpers\ApiResponse;
use App\Helpers\HasActiveOffers;
use App\Helpers\HasAuthor;
use App\Helpers\HasExpiryDate;
use App\Helpers\HasMedia;
use App\Helpers\HasMerchant;
use App\Helpers\HasSearch;
use App\Helpers\HasStatus;
use App\Helpers\HasTimestamps;
use App\Helpers\HasUUID;
use App\Helpers\WeightedModel;
use App\Models\Client;
use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Voucher\Voucher
 *
 * @property int $id
 * @property string $uuid
 * @property int $admin_id
 * @property int|null $image_id
 * @property float|null $price
 * @property string $start_date
 * @property string $expiry_date
 * @property int $weight
 * @property int $status
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \App\Models\Voucher\VoucherLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voucher\VoucherLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher active()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher withTranslation(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher withoutTrashed()
 * @property int|null $merchant_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
 * @property-read Merchant|null $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher activeOffers()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher hasExpiryDate()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher expiryDate()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher hasMerchant()
 * @mixin \Eloquent
 */
class Voucher extends WeightedModel implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes, HasFactory, \App\Helpers\HasTranslations, HasMedia, HasMerchant, HasAuthor, HasStatus, HasExpiryDate, \OwenIt\Auditing\Auditable, HasTimestamps, HasSearch, Translatable, HasUUID, ApiResponse;

    public $translationModel = VoucherLang::class;


    protected $guarded = ['id', 'created_at', 'updated_at'];


    public array $translatedAttributes = [
        'title', 'discount'
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    public function clients(){
        return $this->morphToMany(Client::class, 'redeemable', 'redeemable');
    }

    public function merchant(){
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
