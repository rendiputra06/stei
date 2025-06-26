<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function setValueAttribute($value)
    {
        if ($this->type === 'array' && !is_array($value)) {
            $value = [];
        }
        $this->attributes['value'] = json_encode($value);
    }

    public function getValueAttribute($value)
    {
        $decoded = json_decode($value, true);
        if ($this->type === 'array' && !is_array($decoded)) {
            return [];
        }
        return $decoded;
    }
}
