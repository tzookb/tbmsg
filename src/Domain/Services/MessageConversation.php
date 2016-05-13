<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 15:35
 */

namespace Tzookb\TBMsg\Domain\Services;


use Tzookb\TBMsg\Application\DTO\MessageDTO;
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository;

class MessageConversation
{
    /**
     * @var ConversationRepository
     */
    private $_conversationRepository;

    /**
     * @var MessageStatusRepository
     */
    private $messageStatusRepository;

    /**
     * MessageConversation constructor.
     * @param ConversationRepository $conversationRepository
     * @param MessageStatusRepository $messageStatusRepository
     */
    public function __construct(ConversationRepository $conversationRepository, MessageStatusRepository $messageStatusRepository)
    {
        $this->_conversationRepository = $conversationRepository;
        $this->messageStatusRepository = $messageStatusRepository;
    }

    public function handle(MessageDTO $messageDTO, $conversationId)
    {

        //fetch conversation
        //fail if not found
        $conversation = $this->_conversationRepository->findById($conversationId);

        //fetch participants
        $participants = $this->_conversationRepository->allParticipants($conversationId);


        try {

            $this->_conversationRepository->beginTransaction();

            $message = new Message($messageDTO->senderId, $messageDTO->content);

            //save message
            $newMessageId = $this->_conversationRepository->addMessage($conversationId, $message);


            //save messages statuses per user
            //TODO refactor to one query
            foreach ($participants as $participant) {
                if ($participant == $messageDTO->senderId) {
                    $status = Message::READ;
                    $selfMsg = true;
                } else {
                    $status = Message::UNREAD;
                    $selfMsg = false;
                }

                $this->messageStatusRepository->create(
                    new Message($messageDTO->senderId, $messageDTO->content, null, $newMessageId, $status, $participant)
                );
            }

            $this->_conversationRepository->commit();
        } catch (\Exception $ex) {
            throw $ex;
            $this->_conversationRepository->rollBack();
            return false;
        }

        return true;
    }
}