<?php
namespace Tzookb\TBMsg\Repositories\Eloquent\Objects;
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 3/21/14
 * Time: 6:20 PM
 */

class MessageStatus extends \Eloquent{

    protected $table = 'messages_status';
    public $timestamps = false;

    public function __construct() {

    }
} 