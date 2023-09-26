<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Settings extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Method to fetch a setting
     *
     * @param  string  $key
     * @return bool|string
     */
    public function get(string $key)
    {
        $setting = DB::table($this->table)->where('key', strtolower($key))->first();

        if (!empty($setting)) {
            return $setting->value;
        } else {
            return false;
        }
    }
}
