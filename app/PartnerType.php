<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartnerType
 * @package App
 */
class PartnerType extends Model
{
    /** @var string  */
    protected $table = 'partner_types';
    /** @var array  */
    protected $fillable = [
        'title',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function partner()
    {
        return $this->hasMany('App\PartnerType', 'partner_type_id', 'id');
    }
}
