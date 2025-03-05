<?php

namespace App\Models\Seo;

use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasUploads;
use App\Filament\Helpers\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Seo\Seo
 *
 * @property int $id
 * @property int $user_id
 * @property string $model_type
 * @property int $model_id
 * @property array|null $robots
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin|null $author
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \App\Models\Seo\SeoLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seo\SeoLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Seo listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Seo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo translations()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereRobots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo withTranslation(?string $locale = null)
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereAdminId($value)
 * @mixin \Eloquent
 */
class Seo extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \App\Helpers\HasTranslations, Auditable, HasAuthor, Translatable, HasMedia;

    protected $table = "seo";
    public $translationModel = SeoLang::class;

    public const FOLLOW = "follow";
    public const INDEX = "index";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'robots' => 'array'
    ];

    public array $translatedAttributes = [
        'title', 'description', 'image_id', 'author', 'keywords', 'canonical_url'
    ];

    public array $upload_attributes = [
        'image_id'
    ];


    public static function getRobots(): array
    {
        return [
            self::INDEX => __(self::INDEX),
            self::FOLLOW => __(self::FOLLOW)
        ];
    }
}
