<?php

namespace App\Models\Block;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Block\BlockFeatureLang
 *
 * @property int $id
 * @property int $parent_id
 * @property string $language
 * @property int|null $image_id
 * @property string|null $title
 * @property string|null $description
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang whereTitle($value)
 * @property string|null $second_title
 * @method static \Illuminate\Database\Eloquent\Builder|BlockFeatureLang whereSecondTitle($value)
 * @mixin \Eloquent
 */
class BlockFeatureLang extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'block_features_lang';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
