<?php

namespace App\Models;

use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

/**
 * App\Models\Scan
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Client|null $client
 * @property-read Merchant|null $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|Scan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scan query()
 * @mixin \Eloquent
 */
class Scan extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Auditable;
    protected $table = 'merchant_scans';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function merchant(){
        return $this->hasOne(Merchant::class, 'id', 'merchant_id');
    }

    public function client(){
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}