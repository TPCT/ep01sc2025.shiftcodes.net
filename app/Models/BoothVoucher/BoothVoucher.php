<?php

namespace App\Models\BoothVoucher;

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
use App\Models\Client;
use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\BoothVoucher\BoothVoucher
 *
 * @property int $id
 * @property string $uuid
 * @property int $admin_id
 * @property int|null $image_id
 * @property float $price
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \App\Models\BoothVoucher\BoothVoucherLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BoothVoucher\BoothVoucherLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher active()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher translated()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher withTranslation(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher withoutTrashed()
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Client> $paid_clients
 * @property-read int|null $paid_clients_count
 * @property int|null $merchant_id
 * @property-read Merchant|null $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher activeOffers()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher hasExpiryDate()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher expiryDate()
 * @method static \Illuminate\Database\Eloquent\Builder|BoothVoucher hasMerchant()
 * @mixin \Eloquent
 */
class BoothVoucher extends Model implements Auditable
{
    use SoftDeletes, HasFactory, ApiResponse, \App\Helpers\HasTranslations, HasMerchant, HasMedia, HasAuthor, HasStatus, HasExpiryDate, \OwenIt\Auditing\Auditable, HasTimestamps, HasSearch, Translatable, HasUUID;

    public $translationModel = BoothVoucherLang::class;


    protected $guarded = ['id', 'created_at', 'updated_at'];


    public array $translatedAttributes = [
        'title', 'discount'
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    public function paid_clients(){
        return $this->morphToMany(Client::class, 'redeemable', 'redeemable');
    }
    public function clients(){
        return $this->belongsToMany(Client::class, 'booth_vouchers_clients', 'booth_voucher_id', 'client_id');
    }

    public function merchant(){
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
