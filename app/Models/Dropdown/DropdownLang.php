<?php

namespace App\Models\Dropdown;

use App\Helpers\HasUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dropdown\DropdownLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $description
 * @property string|null $title
 * @property int|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DropdownLang whereTitle($value)
 * @mixin \Eloquent
 */
class DropdownLang extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "dropdowns_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
