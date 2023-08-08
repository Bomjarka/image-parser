<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $web_id
 * @property string $name
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = ['web_id', 'name'];

    public function web()
    {
        return $this->belongsTo(Web::class, 'web_id');
    }
}
