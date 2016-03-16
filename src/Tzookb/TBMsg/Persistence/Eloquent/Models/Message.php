<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 12:10
 */

namespace Tzookb\TBMsg\Persistence\Eloquent\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model {


    public function messagesStatus()
    {
        return $this->hasMany('Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus');
    }

    public function conversation()
    {
        return $this->belongsTo('Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation');
    }

}