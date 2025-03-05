<?php

namespace App\Models\Category;

use App\Helpers\HasUploads;
use App\Models\City\CityLang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\City\CityLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $title
 * @property int|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CityLang whereTitle($value)
 * @mixin \Eloquent
 */
class CategoryLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "categories_lang";
    public $timestamps = false;
    protected $guarded = ['id'];
}
