<?php

namespace App\Models\Faq;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\WeightedModel;
use App\Filament\Helpers\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Faq\Faq
 *
 * @property int $id
 * @property int $user_id
 * @property string $published_at
 * @property int $status
 * @property int $weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \App\Models\Faq\FaqLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Faq\FaqLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @property int $year
 * @method static \Illuminate\Database\Eloquent\Builder|Faq active()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq translations()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq withTranslation(?string $locale = null)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereAdminId($value)
 * @mixin \Eloquent
 */
class Faq extends WeightedModel implements Auditable
{
    use HasFactory, HasStatus, HasAuthor, \OwenIt\Auditing\Auditable, \App\Helpers\HasTranslations, Translatable;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $translationModel = FaqLang::class;

    public array $translatedAttributes = [
        'title',
        'description'
    ];

    public static function getYearsList(){
        $years = [];
        for ($i = date('Y'); $i >= 1990; $i--) {
            $years[$i] = $i;
        }
        return $years;
    }
}
