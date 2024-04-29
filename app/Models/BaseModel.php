<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function getFillables()
    {
        return (new static())->getFillable();
    }

    public static function getInstance($data)
    {
        if (gettype($data) === "string" || gettype($data) === "integer") {
            $data = self::findOrFail($data);
        }
        return $data;
    }
}
