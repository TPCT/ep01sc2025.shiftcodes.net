<?php

namespace App\Models;

use App\Helpers\HasAuthor;
use App\Helpers\HasSlug;
use App\Helpers\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Searchable\Searchable;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Section
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property int $status
 * @property string $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $view
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|Section active()
 * @method static \Illuminate\Database\Eloquent\Builder|Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Section query()
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereView($value)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|Section whereAdminId($value)
 * @mixin \Eloquent
 */
class Section extends Model implements Auditable
{
    use HasFactory, HasAuthor, HasStatus, \OwenIt\Auditing\Auditable, HasSlug;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public const RETAIL_SECTION = "Retail Section";
    public const CORPORATE_SECTION = "Corporate Section";

    public static function getViews(){
        return [
            self::RETAIL_SECTION => __("Retail Section"),
            self::CORPORATE_SECTION => __("Corporate Section"),
        ];
    }

}
