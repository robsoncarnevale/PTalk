<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Storage;

/**
 * CarModel Model
 *
 * @author Davi Souto
 * @since 13/06/2020
 */
class CarModel extends Model
{
    protected $table = 'car_models';

    protected $fillable = [
        'name',
        'car_brand_id',
    ];

    /**
     * car_models.id => car_model_id
     */
    public function car_brand()
    {
        return $this->belongsTo('App\Models\CarBrand');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Models\Vehicle');
    }

    ///////////////////////

    /**
     * {@inheritdoc}
     * Add field photo_url if has photo
     *
     * @author Davi Souto
     * @since 09/06/2020
     * @deprecated 06/08/2020
     */
    // public function toArray()
    // {
    //     $values = parent::toArray();
    //     $values = $this->addPictureUrl($values);

    //     return $values;
    // }

    /**
     * Add field picture_url
     *
     * @param array $values
     * @return array
     * @author Davi Souto
     * @since 23/06/2020
     * @deprecated 06/08/2020
     */
    // private function addPictureUrl($values)
    // {
    //     if (is_array($values) && array_key_exists('picture', $values))
    //     {
    //         $values['picture_url'] = false;

    //         if (! empty($values['picture']))
    //             $values['picture_url'] = Storage::disk('images')->url($values['picture']);
    //     }

    //     return $values;
    // }

    /**
     * Upload car model picture
     * 
     * @author Davi Souto
     * @since 08/09/2020
     */
    public function upload($file)
    {
        $upload_photo = Storage::disk('images')->putFile(getClubCode().'/car_models', $file);

        if ($upload_photo)
        {
            if (! empty($this->picture) && Storage::disk('images')->exists($this->picture)) {
                Storage::disk('images')->delete($this->picture);
            }
            
            $this->picture = $upload_photo;
        }

        return $this;
    }
}
