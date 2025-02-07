<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LicenceUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Application[] $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Server[] $servers
 * @property-read int|null $servers_count
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser whereUsername($value)
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser whereActive($value)
 * @property int $lock_new_devices
 * @method static \Illuminate\Database\Eloquent\Builder|LicenceUser whereLockNewDevices($value)
 */
class LicenceUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function servers(){
        return $this->hasMany(Server::class)->orderBy('updated_at');
    }

    public function applications(){
        return $this->belongsToMany(Application::class, 'server_applications')
                    ->withPivot('active', 'application_id', 'message', 'show_message', 'start_date', 'end_date')
                    ->wherePivot('active', true);
    }
}
