<?php

namespace App\Models\HeaderImage;

use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasStatus;
use App\Helpers\HasUploads;
use App\Filament\Helpers\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\HeaderImage\HeaderImage
 *
 * @property int $id
 * @property int $user_id
 * @property string $path
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \App\Models\HeaderImage\HeaderImageLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HeaderImage\HeaderImageLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage active()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage translated()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage translations()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage withTranslation(?string $locale = null)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImage whereAdminId($value)
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
 * @mixin \Eloquent
 */
class HeaderImage extends Model implements Auditable
{
    use HasFactory, HasStatus, HasAuthor, \OwenIt\Auditing\Auditable, \App\Helpers\HasTranslations, Translatable, HasMedia;

    public $translationModel = HeaderImageLang::class;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public array $translatedAttributes = [
        'title', 'description', 'image_id'
    ];

      public array $upload_attributes = [
          'image_id'
      ];
}
