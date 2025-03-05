<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Reedemable
 *
 * @property int $id
 * @property string $redeemable_type
 * @property int $redeemable_id
 * @property int $client_id
 * @property int|null $merchant_id
 * @property string|null $redeemed_at
 * @property string|null $redeem_token
 * @property int $redeem_rate
 * @property string|null $redeem_comment
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereRedeemComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereRedeemRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereRedeemToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereRedeemableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereRedeemableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereRedeemedAt($value)
 * @property string|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Reedemable whereCreatedAt($value)
 * @mixin \Eloquent
 */
class Reedemable extends Model
{
    use HasFactory;
    protected $table = "redeemable";

    protected $guarded = [];
    public $timestamps = false;
}
