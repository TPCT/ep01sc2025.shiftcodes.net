<?php

namespace App\Models\Seo;

use App\Filament\Helpers\Translatable;
use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasStatus;
use App\Helpers\HasUploads;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Seo\SeoLink
 *
 * @property int $id
 * @property int $user_id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read Media|null $image
 * @property-read Media|null $media
 * @property-read \App\Models\Seo\Seo $seo
 * @property-read \App\Models\Seo\SeoLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seo\SeoLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink translated()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink translations()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink withTranslation(?string $locale = null)
 * @property-read Media|null $cover_image
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLink whereAdminId($value)
 * @mixin \Eloquent
 */
class SeoLink extends Model implements Auditable
{
    use HasFactory, Translatable, HasAuthor, \App\Helpers\HasSeo, \OwenIt\Auditing\Auditable, \App\Helpers\HasTranslations, HasMedia;

    public $translationModel = SeoLang::class;

    public array $translatedAttributes = [
        'title', 'image_id', 'description'
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
