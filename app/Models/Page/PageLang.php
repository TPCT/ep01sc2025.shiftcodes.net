<?php

namespace App\Models\Page;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\WeightedModel;
use App\Models\Faq\Faq;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Page\PageLang
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
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageLang whereTitle($value)
 * @mixin \Eloquent
 */
class PageLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "pages_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
