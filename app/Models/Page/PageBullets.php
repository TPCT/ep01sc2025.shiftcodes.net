<?php

namespace App\Models\Page;

use App\Filament\Helpers\Translatable;
use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasStatus;
use App\Helpers\HasTimestamps;
use App\Helpers\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * App\Models\Page\PageBullets
 *
 * @property int $id
 * @property int $page_id
 * @property int $status
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Page\Page $page
 * @property-read \App\Models\Page\PageBulletsLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Page\PageBulletsLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets active()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets translated()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets translations()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBullets withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
class PageBullets extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Translatable, Auditable, HasStatus, \App\Helpers\HasTranslations;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translationModel = PageBulletsLang::class;

    public array $translatedAttributes = [
        'title', 'description'
    ];

    public function page(){
        return $this->belongsTo(Page::class);
    }
}
