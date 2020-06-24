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

    /**
     * car_models.id => car_model_id
     */
    public function car_brand()
    {
        return $this->belongsTo('App\Models\CarBrand');
    }

    ///////////////////////

    /**
     * {@inheritdoc}
     * Add field photo_url if has photo
     *
     * @author Davi Souto
     * @since 09/06/2020
     */
    public function toArray()
    {
        $values = parent::toArray();
        $values = $this->addPictureUrl($values);

        return $values;
    }

    /**
     * Add field picture_url
     *
     * @param array $values
     * @return array
     * @author Davi Souto
     * @since 23/06/2020
     */
    private function addPictureUrl($values)
    {
        if (is_array($values) && array_key_exists('picture', $values))
        {
            $values['picture_url'] = false;

            if (! empty($values['picture']))
                $values['picture_url'] = Storage::disk('images')->url($values['picture']);
        }

        return $values;
    }
}
