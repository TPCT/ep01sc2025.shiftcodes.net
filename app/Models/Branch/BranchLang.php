<?php

namespace App\Models\Branch;

use App\Helpers\HasUploads;
use App\Models\City\City;
use App\Models\City\CityLang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Branch\BranchLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string $title
 * @property string $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchLang whereTitle($value)
 * @mixin \Eloquent
 */
class BranchLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "branches_lang";
    public $timestamps = false;
    protected $guarded = ['id'];
}
