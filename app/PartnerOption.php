<?php

namespace App;

/**
 * Class PartnerOption
 * @package App
 */
class PartnerOption extends BaseModel
{
    /** @var string */
    const TYPE_KITCHEN = 'kitchen';
    /** @var array */
    const LABELS = [
        'kitchen' => 'Кухня',
    ];
    /** @var array */
    const HIDDEN = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    /** @var string */
    protected $table = 'partner_options';
    /** @var array */
    protected $fillable = [
        'type',
        'name',
    ];
}
