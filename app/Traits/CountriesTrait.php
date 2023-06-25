<?php

namespace App\Traits;

use App\Constants\CountryConstants;

trait CountriesTrait
{
    public function isEuCountry(string $country): bool
    {
        return in_array($country, CountryConstants::EU_COUNTRIES);
    }
}
