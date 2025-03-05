<?php

namespace App\Models\MerchantNotification;

use App\Filament\Helpers\Translatable;
use App\Helpers\HasTimestamps;
use App\Models\Merchant\Merchant;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\MerchantNotification\MerchantNotification
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Merchant|null $merchant
 * @property-read \App\Models\MerchantNotification\MerchantNotificationLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MerchantNotification\MerchantNotificationLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification translated()
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification withTranslation(?string $locale = null)
 * @property int $id
 * @property int $merchant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $merchant_count
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MerchantNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MerchantNotification extends Model implements Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable, \App\Helpers\HasTranslations, HasTimestamps, HasTimestamps;

    protected $table = 'merchants_notifications';
    public $translationModel = MerchantNotificationLang::class;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public array $translatedAttributes = [
        'title', 'description'
    ];

    public function merchant(){
        return $this->hasMany(Merchant::class, 'id', 'merchant_id');
    }
}
