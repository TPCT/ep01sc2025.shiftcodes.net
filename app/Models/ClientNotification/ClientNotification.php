<?php

namespace App\Models\ClientNotification;

use App\Filament\Helpers\Translatable;
use App\Helpers\HasTimestamps;
use App\Models\Client;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\ClientNotification\ClientNotification
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Client|null $client
 * @property-read \App\Models\ClientNotification\ClientNotificationLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClientNotification\ClientNotificationLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification translated()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification withTranslation(?string $locale = null)
 * @property int $id
 * @property int $client_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $client_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification whereUpdatedAt($value)
 * @property string|null $published_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClientNotification wherePublishedAt($value)
 * @mixin \Eloquent
 */
class ClientNotification extends Model implements Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable, \App\Helpers\HasTranslations, HasTimestamps;

    protected $table = 'clients_notifications';
    public $translationModel = ClientNotificationLang::class;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public array $translatedAttributes = [
        'title', 'description'
    ];

    public function client(){
        return $this->hasMany(Client::class, 'id', 'client_id');
    }
}