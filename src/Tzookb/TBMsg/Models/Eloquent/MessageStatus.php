<?php
namespace Tzookb\TBMsg\Models\Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 3/21/14
 * Time: 6:20 PM
 */

class MessageStatus extends Model {

    protected $table = 'messages_status';
    public $timestamps = false;

    public function __construct() {

    }
} 