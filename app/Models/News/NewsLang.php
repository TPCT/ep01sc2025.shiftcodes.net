<?php

namespace App\Models\News;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\WeightedModel;
use App\Models\Faq\Faq;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\News\NewsLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $description
 * @property string|null $title
 * @property string|null $content
 * @property int|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsLang whereTitle($value)
 * @mixin \Eloquent
 */
class NewsLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "news_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
