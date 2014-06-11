<?php
namespace Tzookb\TBMsg\Repositories\Eloquent\Objects;
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 3/21/14
 * Time: 6:20 PM
 */

class User  extends \Eloquent{

    public function msgs_status()
    {
        return $this->hasMany('Tzookb\TBMsg\Repositories\Eloquent\Objects\MessageStatus', 'user_id', 'id');
    }

} 