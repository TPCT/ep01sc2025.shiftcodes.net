<?php

namespace App\Models\Block;

use App\Helpers\HasUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

/**
 * App\Models\Block\BlockLang
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $language
 * @property string|null $content
 * @property string|null $title
 * @property string|null $description
 * @property string|null $second_title
 * @property int|null $image_id
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereSecondTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereTitle($value)
 * @property string|null $youtube_video_id
 * @method static \Illuminate\Database\Eloquent\Builder|BlockLang whereYoutubeVideoId($value)
 * @mixin \Eloquent
 */
class BlockLang extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Auditable;

    protected $guarded = ['id'];
    public $timestamps = false;
    protected $table = "blocks_lang";
}
