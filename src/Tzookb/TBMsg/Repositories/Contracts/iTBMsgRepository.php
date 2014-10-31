<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 7/6/14
 * Time: 10:57 AM
 */

namespace Tzookb\TBMsg\Repositories\Contracts;


interface iTBMsgRepository {

    public function getConversationByTwoUsers($userA_id, $userB_id);
    public function markMessageAs($msgId, $userId, $status);
} 