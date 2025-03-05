<?php

namespace App\Models\SubCategory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\SubCategory\SubCategoryLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string $title
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategoryLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategoryLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategoryLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategoryLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategoryLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategoryLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategoryLang whereTitle($value)
 * @mixin \Eloquent
 */
class SubCategoryLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "sub_categories_lang";
    public $timestamps = false;
    protected $guarded = ['id'];
}
