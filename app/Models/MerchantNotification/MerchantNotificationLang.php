<?php

namespace App\Models\MerchantNotification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\MerchantNotification\MerchantNotificationLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string|null $title
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotificationLang whereTitle($value)
 * @mixin \Eloquent
 */
class MerchantNotificationLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "merchants_notifications_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
