<?php

namespace App\Models\News;

use App\Helpers\HasAuthor;
use App\Helpers\HasLingual;
use App\Helpers\HasMedia;
use App\Helpers\HasSearch;
use App\Helpers\HasSlug;
use App\Helpers\HasStatus;
use App\Helpers\HasTimestamps;
use App\Helpers\HasUploads;
use App\Helpers\WeightedModel;
use App\Models\Dropdown\Dropdown;
use App\Filament\Helpers\Translatable;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\News\News
 *
 * @property int $id
 * @property string|null $header_image
 * @property string|null $header_title
 * @property string|null $header_description
 * @property int $user_id
 * @property string $slug
 * @property string $published_at
 * @property int $status
 * @property-read int|null $images_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $weight
 * @property int $promote_to_homepage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read Media|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Media> $images
 * @property-read Media|null $media
 * @property-read \App\Models\Seo\Seo $seo
 * @property-read \App\Models\News\NewsLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\News\NewsLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|News active()
 * @method static \Illuminate\Database\Eloquent\Builder|News listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|News orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|News orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|News orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News translated()
 * @method static \Illuminate\Database\Eloquent\Builder|News translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|News translations()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereHeaderDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereHeaderImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereHeaderTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News wherePromoteToHomepage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News withTranslation(?string $locale = null)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|News whereAdminId($value)
 * @property-read Media|null $cover_image
 * @mixin \Eloquent
 */
class News extends WeightedModel implements \OwenIt\Auditing\Contracts\Auditable, Searchable
{
    use HasFactory, \App\Helpers\HasTranslations, HasMedia, HasAuthor, HasStatus, \OwenIt\Auditing\Auditable, \App\Helpers\HasSeo, HasSlug, HasTimestamps, HasSearch, Translatable;

    public $translationModel = NewsLang::class;


    protected $guarded = ['id', 'created_at', 'updated_at'];


    public array $translatedAttributes = [
        'title', 'description', 'content', 'image_id'
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('news.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    public function images(){
        return $this->belongsToMany(Media::class, 'news_images', 'parent_id', 'image_id');
    }

}
