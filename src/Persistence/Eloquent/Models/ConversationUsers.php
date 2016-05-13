<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 12:10
 */

namespace Tzookb\TBMsg\Persistence\Eloquent\Models;


use Illuminate\Database\Eloquent\Model;

class ConversationUsers  extends Model {

    protected $table = 'tbm_conv_users';
    protected $fillable = ['user_id'];
    public $timestamps = false;

    public function conversation()
    {
        return $this->belongsTo('Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation', 'id', 'conv_id');
    }

}