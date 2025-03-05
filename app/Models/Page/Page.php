<?php

namespace App\Models\Page;

use App\Helpers\HasAuthor;
use App\Helpers\HasSearch;
use App\Helpers\HasSlug;
use App\Helpers\HasStatus;
use App\Helpers\HasUploads;
use App\Settings\Site;
use App\Filament\Helpers\Translatable;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Page\Page
 *
 * @property int $id
 * @property int $user_id
 * @property string $slug
 * @property string|null $prefix
 * @property string $view
 * @property string $published_at
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $direct_access
 * @property string|null $video_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Page\PageBullets> $bullets
 * @property-read int|null $bullets_count
 * @property-read mixed $link
 * @property-read array|null $section_ids
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Media> $images
 * @property-read int|null $images_count
 * @property-read Media|null $media
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Section> $sections
 * @property-read int|null $sections_count
 * @property-read \App\Models\Seo\Seo $seo
 * @property-read \App\Models\Page\PageLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Page\PageLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|Page active()
 * @method static \Illuminate\Database\Eloquent\Builder|Page directAccess()
 * @method static \Illuminate\Database\Eloquent\Builder|Page listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Page orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Page orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Page orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page section($section)
 * @method static \Illuminate\Database\Eloquent\Builder|Page translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Page translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Page translations()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDirectAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page withTranslation(?string $locale = null)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereAdminId($value)
 * @mixin \Eloquent
 */
class Page extends Model implements Auditable, Searchable
{
    use HasFactory, \App\Helpers\HasTranslations, HasAuthor, HasStatus, \OwenIt\Auditing\Auditable, \App\Helpers\HasSeo, HasSlug, \App\Helpers\HasSection, Translatable, HasSearch;

    public const ADMISSION_VIEW = "Admission View";
    public const CHAIRMAN_LETTER_VIEW = "Chairman-Letter View";

    public static function getViews(){
        return [
            self::ADMISSION_VIEW => __(self::ADMISSION_VIEW),
            self::CHAIRMAN_LETTER_VIEW => __(self::CHAIRMAN_LETTER_VIEW),
        ];
    }

    public $translationModel = PageLang::class;

    protected $appends = ['link'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public array $translatedAttributes = [
        'title', 'keywords', 'description', 'content'
    ];

    public function scopeDirectAccess(): Page|\Illuminate\Database\Eloquent\Builder
    {
        return $this->where('direct_access', true);
    }

    public function getLinkAttribute(){
        $link = "/" . app()->getLocale() . "/";
        if ($this->prefix)
            $link .= $this->prefix . "/";
        $link .= $this->slug;
        return $link;
    }

    public function getSearchResult(): SearchResult
    {
        $url = $this->link;

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    public static function results($model){
        $query = self::directAccess()->where(function($query) use ($model){
            foreach(self::$models[$model]['fields'] as $key){
                $query->orWhereTranslationLike($key, '%'.request('search', '').'%');
            }
        });

        $results = $query->limit(app(Site::class)->search_page_size * request('page', 1));
        $has_more = $query->count() > $results->count();
        return [
            'results' => $results->get(),
            'has_more' => $has_more,
        ];
    }

    public function getSearchUrl(){
        $url = "/";
        if ($this->sections?->count())
            $url .= $this->sections->first()->slug . "/";
        if ($this->prefix)
            $url .= $this->prefix . "/";
        return $url . $this->slug;
    }

    public function bullets(){
        return $this->hasMany(PageBullets::class);
    }

    public function images(){
        return $this->
        belongsToMany(Media::class, 'page_images', 'page_id', 'media_id')
            ->withPivot('order')
            ->orderBy('order');    }
}
