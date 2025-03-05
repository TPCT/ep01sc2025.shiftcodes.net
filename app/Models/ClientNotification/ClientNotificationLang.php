<?php

namespace App\Models\ClientNotification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\ClientNotification\ClientNotificationLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string|null $title
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotificationLang whereTitle($value)
 * @mixin \Eloquent
 */
class ClientNotificationLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "clients_notifications_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
