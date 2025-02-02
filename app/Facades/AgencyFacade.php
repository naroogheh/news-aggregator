<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AgencyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'agency-helper';
    }
}
