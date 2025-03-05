<?php

namespace App\Models;

use App\Helpers\HasAuthor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\FileExtension
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $extension
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension query()
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension whereUserId($value)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|FileExtension whereAdminId($value)
 * @mixin \Eloquent
 */
class FileExtension extends Model implements Auditable
{
    use HasFactory, HasAuthor, \OwenIt\Auditing\Auditable;
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
