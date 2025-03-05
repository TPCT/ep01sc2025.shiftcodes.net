<?php

namespace App\Models\Seo;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\WeightedModel;
use App\Models\Faq\Faq;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Seo\SeoLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $author
 * @property string|null $title
 * @property string|null $description
 * @property string|null $canonical_url
 * @property int|null $image_id
 * @property array|null $keywords
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereCanonicalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoLang whereTitle($value)
 * @mixin \Eloquent
 */
class SeoLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "seo_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'keywords' => 'array'
    ];
}
