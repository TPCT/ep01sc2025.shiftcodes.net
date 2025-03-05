<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Visitor
 *
 * @property int $id
 * @property string|null $method
 * @property string|null $request
 * @property string|null $url
 * @property string|null $referer
 * @property string|null $languages
 * @property string|null $useragent
 * @property string|null $headers
 * @property string|null $device
 * @property string|null $platform
 * @property string|null $browser
 * @property string|null $ip
 * @property string|null $visitable_type
 * @property int|null $visitable_id
 * @property string|null $visitor_type
 * @property int|null $visitor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read Admin|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereHeaders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereUseragent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereVisitableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereVisitableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereVisitorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereVisitorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
class Visitor extends Model
{
    protected $table = "shetabit_visits";
    use HasFactory, HasRoles;

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Admin::class, 'id', 'visitor_id');
    }
}
