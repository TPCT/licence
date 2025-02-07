<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Application
 *
 * @property int $id
 * @property string $application_name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Server[] $servers
 * @property-read int|null $servers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Application newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application query()
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereApplicationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float $application_version
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereApplicationVersion($value)
 */
class Application extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public const TRIAL = 0;
    public const ONE_MONTH = 1;
    public const THREE_MONTHS = 3;
    public const SIX_MONTHS = 6;
    public const NINE_MONTHS = 9;
    public const TWELVE_MONTHS = 12;
    public const LIFE_TIME = 24;

    public static function Packages(){
        return [
            self::TRIAL => "Trial",
            self::ONE_MONTH => "1 Month",
            self::THREE_MONTHS => "3 Months",
            self::SIX_MONTHS => "6 Months",
            self::NINE_MONTHS => "9 Months",
            self::TWELVE_MONTHS => "12 Months",
            self::LIFE_TIME => "Life time",
        ];
    }

    public function servers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Server::class, 'server_applications')->withTimestamps();
    }

    public function users(){
        $this->belongsToMany(LicenceUser::class, 'server_applications')->withTimestamps();
    }
}
