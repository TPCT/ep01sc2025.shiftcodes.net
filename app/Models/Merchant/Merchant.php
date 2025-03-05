<?php

namespace App\Models\Merchant;

use App\Helpers\ApiResponse;
use App\Helpers\HasActiveOffers;
use App\Helpers\HasMedia;
use App\Helpers\HasVerification;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Branch\Branch;
use App\Models\Category\Category;
use App\Models\Client;
use App\Models\MerchantNotification\MerchantNotification;
use App\Models\Offer\Offer;
use App\Models\Reedemable;
use App\Models\Scan;
use App\Models\SubCategory\SubCategory;
use App\Models\Voucher\Voucher;
use App\Settings\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Number;
use OwenIt\Auditing\Auditable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\Merchant
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereUpdatedAt($value)
 * @property int|null $image_id
 * @property int|null $cover_image_id
 * @property int $category_id
 * @property string $name
 * @property string $country_code
 * @property string|null $phone
 * @property string|null $email
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Category $category
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Voucher> $vouchers
 * @property-read int|null $vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCoverImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant withoutTrashed()
 * @property string|null $pin_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SubCategory> $sub_categories
 * @property-read int|null $sub_categories_count
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePinId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Branch> $branches
 * @property-read int|null $branches_count
 * @property int $exists_in_mall
 * @property int $exists_in_avenue
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereExistsInAvenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereExistsInMall($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Offer> $offers
 * @property-read int|null $offers_count
 * @property-read \App\Models\Merchant\MerchantDetails|null $details
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Reedemable> $redeemable
 * @property-read int|null $redeemable_count
 * @property int $notification
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BoothVoucher> $booth_vouchers
 * @property-read int|null $booth_vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant verification()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereNotification($value)
 * @property string|null $fcm_token
 * @property string|null $mobile_type
 * @property int $verified
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMobileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant activeOffers()
 * @property-read mixed $has_branches
 * @property string|null $published_at
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePublishedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Scan> $scans
 * @property-read int|null $scans_count
 * @mixin \Eloquent
 */
class Merchant extends \Illuminate\Foundation\Auth\User implements JWTSubject, \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Notifiable, SoftDeletes, Auditable, ApiResponse, HasMedia, HasVerification, HasActiveOffers;
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['has_branches'];

    public static function boot(){
        parent::boot();
        self::creating(function ($model) {
            if (!$model->image_id)
                $model->image_id = app(Site::class)->merchant_default_image ?? 0;
            if (!$model->cover_image_id)
                $model->cover_image_id = app(Site::class)->merchant_default_cover_image ?? 0;
        });
    }

    public function getHasBranchesAttribute(){
        return $this->branches->count() ? 1 : 0;
    }

    public array $upload_attributes = [
        'image_id', 'cover_image_id'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function booth_vouchers(){
        return $this->hasMany(BoothVoucher::class, 'merchant_id');
    }

    public function vouchers(){
        return $this->hasMany(Voucher::class, 'merchant_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function sub_categories(){
        return $this
            ->belongsToMany(SubCategory::class, 'merchants_sub_categories', 'merchant_id', 'sub_category_id');
    }

    public function branches(){
        return $this->hasMany(Branch::class, 'merchant_id');
    }

    public function offers(){
        return $this->hasMany(Offer::class, 'merchant_id');
    }

    public function rating(){
        $query = \DB::table("redeemable")->where("merchant_id", $this->id)->selectRaw("SUM(redeem_rate) as rating, COUNT(id) as count")->get();

        if (!$query->first()->count)
            return [
                'rate' => 0,
                'count' => 0
            ];

        return [
            'rate' => round((float)Number::format($query->first()->rating / $query->first()->count, 2)),
            'count' => $query->first()->count
        ];
    }

    public function details(){
        return $this->hasOne(MerchantDetails::class, 'merchant_id');
    }

    public function redeemable(){
        return $this->hasMany(Reedemable::class);
    }

    public function notifications()
    {
        return $this->hasMany(MerchantNotification::class, 'merchant_id');
    }

    public function scans(){
        return $this->hasMany(Scan::class, 'merchant_id', 'id');
    }
}
