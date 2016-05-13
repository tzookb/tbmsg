<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 12:10
 */

namespace Tzookb\TBMsg\Persistence\Eloquent\Models;


use Illuminate\Database\Eloquent\Model;

class Conversation  extends Model {

    protected $table = 'tbm_conversations';

    public function conversationUsers()
    {
        return $this->hasMany('Tzookb\TBMsg\Persistence\Eloquent\Models\ConversationUsers', 'conv_id', 'id');
    }

}