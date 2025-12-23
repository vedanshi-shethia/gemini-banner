<?php

namespace Vedanshi\GeminiBanner\Facades;

use Illuminate\Support\Facades\Facade;

class GeminiBanner extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vedanshi\GeminiBanner\Services\GeminiBannerService::class;
    }
}
