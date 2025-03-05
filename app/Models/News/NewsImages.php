<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\News\NewsImages
 *
 * @property int $id
 * @property int $parent_id
 * @property int $image_id
 * @property-read \App\Models\News\News $news
 * @method static \Illuminate\Database\Eloquent\Builder|NewsImages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsImages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsImages query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsImages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsImages whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsImages whereParentId($value)
 * @mixin \Eloquent
 */
class NewsImages extends Model
{
    use HasFactory;

    public function news(){
        return $this->BelongsTo(News::class, 'parent_id', 'id');
    }
}
