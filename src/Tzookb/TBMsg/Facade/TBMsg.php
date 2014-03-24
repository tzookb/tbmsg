<?php 

namespace Tzookb\TBMsg\Facade;

use Illuminate\Support\Facades\Facade;

class TBMsg extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'tbmsg'; }

}