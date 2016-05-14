<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:17
 */

namespace Tzookb\TBMsg\Domain\Repositories;


use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;

interface ConversationRepository extends BaseRepository
{
    /**
     * @param Conversation $conversation
     * @return integer
     */
    public function create(Conversation $conversation);

    /**
     * @param $conversationId
     * @param Message $message
     * @return boolean
     */
    public function addMessage($conversationId, Message $message);

    /**
     * @param $conversationId
     * @return integer[]
     */
    public function allParticipants($conversationId);

    /**
     * @param $conversationId
     */
    public function findById($conversationId);

    /**
     * @param $userId
     * @return integer[]
     */
    public function allByUserId($userId);

    /**
     * @param $userId
     * @param $convId
     * @return array
     */
    public function getMessagesOfConversationForUser($userId, $convId);

    /**
     * update all conversation messages as read,
     * for specific user in conversation.
     * return amount of messages that were changed.
     *
     * @param $userId
     * @param $convId
     * @return integer
     */
    public function markConversationAsRead($userId, $convId);

    /**
     * @param integer $userIdA
     * @param integer $userIdB
     * @return integer
     */
    public function findByTwoUsers($userIdA, $userIdB);


}