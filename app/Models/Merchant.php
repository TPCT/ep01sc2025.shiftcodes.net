<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use App\Helpers\HasMedia;
use App\Models\Category\Category;
use App\Models\Voucher\Voucher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
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
 * @mixin \Eloquent
 */
class Merchant extends \Illuminate\Foundation\Auth\User implements JWTSubject, \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Notifiable, SoftDeletes, Auditable, HasMedia, ApiResponse;
    protected $guarded = [];
    protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at'];

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

    public function vouchers(){
        return $this->hasMany(Voucher::class, 'merchant_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
