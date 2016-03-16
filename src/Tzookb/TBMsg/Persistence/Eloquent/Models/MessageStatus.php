<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 12:10
 */

namespace Tzookb\TBMsg\Persistence\Eloquent\Models;


use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model {

    public function message()
    {
        return $this->belongsTo('Tzookb\TBMsg\Persistence\Eloquent\Models\Message');
    }

}