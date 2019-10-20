<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Offer
 * @package App
 */
class Offer extends Model
{
    /** @var int  */
    const BASE_OFFER_TYPE = 1;
    /** @var int  */
    const SPECIAL_OFFER_TYPE = 2;
    /** @var int  */
    const DATE_BASE_OFFER_TYPE = 3;

    /** @var string  */
    protected $table = 'offers';
    /** @var array  */
    protected $fillable = [
        'partner_id',
        'type',
        'start_date',
        'finish_date',
        'description',
        'value',
    ];

    /** @var array  */
    protected $appends = [
        'type_string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * @return array
     */
    public static function typeList(): array
    {
        return [
            self::BASE_OFFER_TYPE => 'Обычный',
            self::SPECIAL_OFFER_TYPE => 'Спецпредложение',
            self::DATE_BASE_OFFER_TYPE => 'Период',
        ];
    }

    /**
     * @return string
     */
    public function getTypeStringAttribute(): string
    {
        return self::typeList()[$this->type] ?? '';
    }
}
