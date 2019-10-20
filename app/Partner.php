<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Partner
 * @package App
 */
class Partner extends Model
{
    /** @var string  */
    protected $table = 'partners';

    /** @var array  */
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
}
