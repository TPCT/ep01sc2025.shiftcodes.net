<?php

namespace App\Models\Slider;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\WeightedModel;
use App\Models\Faq\Faq;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Slider\SliderLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $title
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SliderLang whereTitle($value)
 * @mixin \Eloquent
 */
class SliderLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "sliders_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
