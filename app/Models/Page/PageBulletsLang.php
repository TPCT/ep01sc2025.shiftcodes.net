<?php

namespace App\Models\Page;

use App\Filament\Helpers\Translatable;
use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasStatus;
use App\Helpers\HasTimestamps;
use App\Helpers\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * App\Models\Page\PageBulletsLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string $title
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBulletsLang whereTitle($value)
 * @mixin \Eloquent
 */
class PageBulletsLang extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "page_bullets_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
