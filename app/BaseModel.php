<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package App
 */
class BaseModel extends Model
{
    /** @var array */
    const HIDDEN = [];

    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (!empty(request()->route()->controller->hideFields) && static::HIDDEN) {
            $this->hidden = static::HIDDEN;
        }
    }
}
