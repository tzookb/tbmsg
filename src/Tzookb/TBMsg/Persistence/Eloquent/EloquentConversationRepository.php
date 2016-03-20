<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:40
 */

namespace Tzookb\TBMsg\Persistence\Eloquent;


use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\Models\ConversationUsers;

class EloquentConversationRepository extends EloquentBaseRepository implements ConversationRepository
{
    /**
     * @param Conversation $conversation
     * @return integer
     */
    public function create(Conversation $conversation)
    {
        $eloquentConversation = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation();

        $eloquentConversation->save();

        $participants = array_map(function($participant) {
            return new ConversationUsers(['user_id'=>$participant]);
        }, $conversation->getParticipants());


        $eloquentConversation->conversationUsers()->saveMany($participants);

        return $eloquentConversation->id;
    }

    /**
     * @param $conversationId
     * @return integer[]
     */
    public function allParticipants($conversationId)
    {
        $eloquentConversation = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation();
        $eloquentConversation = $eloquentConversation->findOrFail($conversationId);

        return array_map(function($conversationUser) {
            return $conversationUser['user_id'];
        }, $eloquentConversation->conversationUsers->toArray());
    }

    /**
     * @param $conversationId
     * @param Message $message
     * @return boolean|integer
     */
    public function addMessage($conversationId, Message $message)
    {
        $eloquentMessage = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Message();
        $eloquentMessage->sender_id = $message->getCreator();
        $eloquentMessage->conv_id = $conversationId;
        $eloquentMessage->content = $message->getContent();

        $eloquentMessage->save();
        return $eloquentMessage->id;
    }

    /**
     * @param $conversationId
     */
    public function findById($conversationId)
    {
        $eloquentConversation = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation();
        $conversation = $eloquentConversation->findOrFail($conversationId);
        return $conversation;
    }

    /**
     * @param $userId
     * @return \integer[]
     */
    public function allByUserId($userId)
    {
        $eloquentConversationUsers = new \Tzookb\TBMsg\Persistence\Eloquent\Models\ConversationUsers();
        $conversations = $eloquentConversationUsers
                            ->select('conv_id')
                            ->where([
                                'user_id' => $userId,
                                'active' => true
                            ])->get();

        return $conversations->map(function($row) {
            return (int)$row->conv_id;
        });

    }


    /**
     * @param $userId
     * @param $convId
     * @return mixed
     */
    public function getMessagesOfConversationForUser($userId, $convId)
    {
        $eloquentMessage = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Message();

        $res = $eloquentMessage
            ->select([
                'messages.id', 'messages.sender_id', 'messages.content',
                'messages.created_at', 'message_statuses.status',
                'message_statuses.user_id'
            ])
            ->where('messages.conv_id', $convId)
            ->where('message_statuses.user_id', $userId)
            ->join('message_statuses', 'messages.id', '=', 'message_statuses.msg_id')
            ->get();

        return $res->toArray();
    }

    public function markConversationAsRead($userId, $convId)
    {
        $eloquentMessage = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Message();
        $eloquentMessageStatus = new \Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus();

        $messagesIdsInConv = $eloquentMessage
            ->select('messages.id')
            ->where('messages.conv_id', $convId)
            ->get()->map(function($row) {
                return $row->id;
            });

        $res = $eloquentMessageStatus
            ->whereIn('msg_id', $messagesIdsInConv)
            ->where('user_id', $userId)
            ->update(['status' => Message::READ]);

        return $res;
    }

    public function findByTwoUsers($userIdA, $userIdB)
    {
        //todo, this is a problem, as if user has severl conversations with same user alone
        //they will have many conversation in the result, in general only one is desired.

        $eloquentConversationUsers = new \Tzookb\TBMsg\Persistence\Eloquent\Models\ConversationUsers();
        $res = $eloquentConversationUsers
            ->select('conv_id')
            ->whereIn('user_id', [$userIdA, $userIdB])
            ->groupBy('conv_id')
            ->havingRaw('COUNT(conv_id) = 2')
            ->get();

        if ($res->isEmpty())
            return null;

        return $res[0]['conv_id'];
    }
}