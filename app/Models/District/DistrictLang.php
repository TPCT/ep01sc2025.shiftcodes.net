<?php

namespace App\Models\District;

use App\Helpers\HasAuthor;
use App\Helpers\HasStatus;
use App\Helpers\HasUploads;
use App\Models\City\City;
use App\Filament\Helpers\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\District\DistrictLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $title
 * @property int|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DistrictLang whereTitle($value)
 * @mixin \Eloquent
 */




class DistrictLang extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "districts_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
