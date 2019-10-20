<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Check
 * @package App
 */
class Check extends Model
{
    /** @var int  */
    const STATUS_CHECKING = 1;
    /** @var int  */
    const STATUS_DECLINED = 2;
    /** @var int  */
    const STATUS_ACCEPTED = 3;
    /** @var int */
    const STATUS_READY_TO_PAY = 4;
    /** @var int  */
    const STATUS_PAYED = 5;

    /** @var array  */
    protected $fillable = [
        'user_id',
        'partner_id',
        'status',
        'status_description',
        'sum',
        'offer_id',
        'cashback_sum',
    ];

    protected $appends = [
        'status_string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class, 'object_id', 'id')
            ->where('object_type', Image::TYPE_CHECK);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * @return array
     */
    public static function statusList(): array
    {
        return [
            self::STATUS_CHECKING => 'В обработке',
            self::STATUS_ACCEPTED => 'Принят',
            self::STATUS_DECLINED => 'Отклонен',
            self::STATUS_READY_TO_PAY => 'К выплате',
            self::STATUS_PAYED => 'Начислено',
        ];
    }

    /**
     * @return string
     */
    public function getStatusStringAttribute(): string
    {
        return self::statusList()[$this->status] ?? '';
    }
}
