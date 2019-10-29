<?php

namespace App;

/**
 * Class PartnerType
 * @package App
 */
class PartnerType extends BaseModel
{
    /** @var array */
    const HIDDEN = [
        'created_at',
        'updated_at',
    ];
    /** @var string */
    protected $table = 'partner_types';
    /** @var array */
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
