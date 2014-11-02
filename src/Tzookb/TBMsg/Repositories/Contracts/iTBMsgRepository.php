<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 7/6/14
 * Time: 10:57 AM
 */

namespace Tzookb\TBMsg\Repositories\Contracts;


interface iTBMsgRepository {

    const DELETED = 0;
    const UNREAD = 1;
    const READ = 2;
    const ARCHIVED = 3;

    public function createConversation( $users_ids );
    public function addMessageToConversation($conv_id, $user_id, $content);
    public function getConversationByTwoUsers($userA_id, $userB_id);
    public function isUserInConversation($conv_id, $user_id);
    public function getUsersInConversation($conv_id);
    public function getNumOfUnreadMsgs($user_id);

    public function markReadAllMessagesInConversation($conv_id, $user_id);
    public function deleteConversation($conv_id, $user_id);

    public function getConversationMessages($conv_id, $user_id, $newToOld=true);
    public function getConversations($user_id);
    public function getUsersInConvs($convsIds);

    public function markMessageAs($msgId, $userId, $status);
    public function markMessageAsRead($msgId, $userId);
    public function markMessageAsUnread($msgId, $userId);
    public function markMessageAsDeleted($msgId, $userId);
    public function markMessageAsArchived($msgId, $userId);
}