<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 7/6/14
 * Time: 10:58 AM
 */

namespace Tzookb\TBMsg\Repositories;


use Tzookb\TBMsg\Repositories\Contracts\iTBMsgRepository;
use DB;

class EloquentTBMsgRepository implements iTBMsgRepository
{
    protected $usersTable;
    protected $usersTableKey;
    protected $tablePrefix;

    public function __construct($tablePrefix, $usersTable, $usersTableKey) {
        $this->usersTable = $usersTable;
        $this->usersTableKey = $usersTableKey;
        $this->tablePrefix = $tablePrefix;
    }

    public function getConversationByTwoUsers($userA_id, $userB_id) {
        $results = DB::select(
            '
            SELECT cu.conv_id
            FROM conv_users cu
            WHERE cu.user_id=? OR cu.user_id=?
            GROUP BY cu.conv_id
            HAVING COUNT(cu.conv_id)=2
            '
            , array($userA_id, $userB_id));
        if( count($results) == 1 ) {
            return (int)$results[0]->conv_id;
        }
        return -1;
    }

    public function markMessageAs($msgId, $userId, $status) {
        DB::statement(
            '
            UPDATE '.$this->tablePrefix.'messages_status mst
            SET mst.status=?
            WHERE mst.user_id=?
            AND mst.msg_id=?
            ',
            array($status, $userId, $msgId)
        );
    }
} 