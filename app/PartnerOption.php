<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartnerOption
 * @package App
 */
class PartnerOption extends Model
{
    /** @var string  */
    const TYPE_KITCHEN = 'kitchen';
    /** @var array  */
    const LABELS = [
        'kitchen' => 'Кухня',
    ];

    /** @var string  */
    protected $table = 'partner_options';
    /** @var array  */
    protected $fillable = [
        'type',
        'name',
    ];
}
