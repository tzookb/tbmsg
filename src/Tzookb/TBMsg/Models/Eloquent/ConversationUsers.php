<?php
namespace Tzookb\TBMsg\Models\Eloquent;
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 3/21/14
 * Time: 6:20 PM
 */

class ConversationUsers  extends \Eloquent{
    protected $table = 'conv_users';
    public $timestamps = false;

    public function __construct() {
    }
} 