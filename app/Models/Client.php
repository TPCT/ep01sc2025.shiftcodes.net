<?php

namespace App\Models;

use App\Models\BoothVoucher\BoothVoucher;
use App\Models\ClientNotification\ClientNotification;
use App\Models\Offer\Offer;
use App\Models\Voucher\Voucher;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\Client
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @property int $id
 * @property string $name
 * @property string $country_code
 * @property string|null $phone
 * @property string|null $email
 * @property int $points
 * @property string|null $pin_id
 * @property int $active 0 -> inactive, 1 -> active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BoothVoucher> $booth_vouchers
 * @property-read int|null $booth_vouchers_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Client onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Voucher> $vouchers
 * @property-read int|null $vouchers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BoothVoucher> $paid_booth_vouchers
 * @property-read int|null $paid_booth_vouchers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Voucher> $paid_vouchers
 * @property-read int|null $paid_vouchers_count
 * @property string|null $google_id
 * @property string|null $facebook_id
 * @property string|null $instagram_id
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereInstagramId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Offer> $offers
 * @property-read int|null $offers_count
 * @property int $notification
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereNotification($value)
 * @property string|null $fcm_token
 * @property string|null $mobile_type
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereMobileType($value)
 * @property string|null $apple_id
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAppleId($value)
 * @mixin \Eloquent
 */
class Client extends \Illuminate\Foundation\Auth\User implements JWTSubject, \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Notifiable, SoftDeletes, Auditable;
    protected $guarded = [];
    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public const IOS = "ios";
    public const ANDROID = "android";

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function booth_vouchers(){
        return $this->belongsToMany(BoothVoucher::class, 'booth_vouchers_clients', 'client_id', 'booth_voucher_id');
    }

    public function paid_vouchers(){
        return $this->morphedByMany(Voucher::class, 'redeemable', 'redeemable', 'client_id', 'redeemable_id');
    }

    public function paid_booth_vouchers(){
        return $this->morphedByMany(BoothVoucher::class, 'redeemable', 'redeemable', 'client_id', 'redeemable_id');
    }

    public function offers(){
        return $this->morphedByMany(Offer::class, 'redeemable', 'redeemable', 'client_id', 'redeemable_id');
    }

    public function notifications(){
        return $this->hasMany(ClientNotification::class, 'client_id');
    }
}
