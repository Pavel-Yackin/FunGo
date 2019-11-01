<?php

namespace App;

/**
 * Class Partner
 * @package App
 */
class Partner extends BaseModel
{
    /** @var array */
    const HIDDEN = [
        'partner_type_id',
        'schedule',
        'description',
        'cashback_description',
        'longitude',
        'latitude',
        'created_at',
        'updated_at',

        'start_date',
        'finish_date',
        'partner_id',
        'type',
        'value',
    ];

    /** @var string */
    protected $table = 'partners';

    /** @var array */
    protected $fillable = [
        'name',
        'partner_type_id',
        'address',
        'phone',
        'mail',
        'description',
        'cashback_description',
        'logo',
        'schedule',
        'latitude',
        'longitude',
        'top',
        'min_check',
    ];

    /** @var array */
    protected $appends = [
        'distance'
    ];

    /** @var array */
    const SINGLE_HIDDEN = [
        'partner_type_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partnerType()
    {
        return $this->belongsTo('App\PartnerType', 'partner_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class, 'object_id', 'id')
            ->where('object_type', Image::TYPE_PARTNER);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(Offer::class, 'partner_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function partnerOptions()
    {
        return $this->belongsToMany(PartnerOption::class, 'partner_options_relation', 'partner_id',
            'partner_option_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function partner_options()
    {
        return $this->belongsToMany(PartnerOption::class, 'partner_options_relation', 'partner_id',
            'partner_option_id');
    }

    /**
     * @return array
     */
    public static function partnerList(): array
    {
        $partners = Partner::query()
            ->select(['id', 'name', 'address'])
            ->orderBy('name')
            ->get();

        $result = [];
        foreach ($partners as $partner) {
            $result[$partner->id] = "{$partner->name}: {$partner->address}";
        }

        return $result;
    }

    /**
     * @return float|int|null
     */
    public function getDistanceAttribute()
    {
        $lat = request('latitude');
        $long = request('longitude');

        if ($lat && $long && $this->latitude && $this->longitude) {
            $latFrom = deg2rad((float)$lat);
            $lonFrom = deg2rad((float)$long);
            $latTo = deg2rad($this->latitude);
            $lonTo = deg2rad($this->longitude);

            $lonDelta = $lonTo - $lonFrom;

            return 6371000 * (acos(sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta)));
        }

        return null;
    }
}
