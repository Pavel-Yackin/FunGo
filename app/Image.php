<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App
 */
class Image extends Model
{
    /** @var int  */
    const TYPE_PARTNER = 1;
    /** @var int  */
    const TYPE_CHECK = 2;

    /** @var string  */
    protected $table = 'images';

    /** @var array  */
    protected $fillable = [
        'object_type',
        'object_id',
        'path',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'id', 'object_id')
            ->where('object_type', self::TYPE_PARTNER);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function check()
    {
        return $this->belongsTo(Check::class, 'id', 'object_id')
            ->where('object_type', self::TYPE_CHECK);
    }
}
