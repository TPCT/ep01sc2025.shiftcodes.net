<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Menu\MenuLinkLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property string $title
 * @property string $link
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuLinkLang whereTitle($value)
 * @mixin \Eloquent
 */
class MenuLinkLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "menu_links_lang";
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
