<?php

namespace App\Models\HeaderImage;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\WeightedModel;
use App\Models\Faq\Faq;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\HeaderImage\HeaderImageLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $description
 * @property string|null $title
 * @property int|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderImageLang whereTitle($value)
 * @mixin \Eloquent
 */
class HeaderImageLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "header_images_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
