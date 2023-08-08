<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $url
 * @property string $host
 */
class Web extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'host'];

    public function images()
    {
        return $this->hasMany(Image::class, 'web_id');
    }
}
