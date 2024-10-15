<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Config;

trait SerializeDate
{

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param Datetime $date
     * @return string
     */
    protected function serializeDate($date)
    {
        return $date->setTimezone(Config::get('app.timezone', 'Asia/Tokyo'));
    }
}
