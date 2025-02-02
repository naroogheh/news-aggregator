<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AuthorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'author-helper';
    }
}
