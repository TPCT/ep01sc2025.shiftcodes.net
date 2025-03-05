<?php

namespace App\Models\Faq;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\WeightedModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Faq\FaqLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $description
 * @property string|null $title
 * @property int|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqLang whereTitle($value)
 * @mixin \Eloquent
 */
class FaqLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "faqs_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
