<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Server
 *
 * @property int $id
 * @property string $server_ip
 * @property string $server_name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Application[] $application
 * @property-read int|null $application_count
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereServerIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereServerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Application[] $applications
 * @property-read int|null $applications_count
 * @property int $licence_user_id
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereLicenceUserId($value)
 * @property string|null $server_mac_address
 * @property-read \App\Models\LicenceUser|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereServerMacAddress($value)
 */
class Server extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function applications(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Application::class, 'server_applications')->withPivot(['active', 'licence_date', 'message', 'show_message', 'start_date', 'end_date'])->orderByPivot('licence_date');
    }

    public function user(){
        return $this->hasOne(LicenceUser::class, 'id', 'licence_user_id');
    }
}
