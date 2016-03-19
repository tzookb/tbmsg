<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 19/03/16
 * Time: 17:32
 */

namespace Tzookb\TBMsg\Domain\Services;


use Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository;

class GetNumOfUnreadMessages
{
    /**
     * @var MessageStatusRepository
     */
    private $_messageStatusRepository;

    /**
     * GetNumOfUnreadMessages constructor.
     * @param MessageStatusRepository $messageStatusRepository
     */
    public function __construct(MessageStatusRepository $messageStatusRepository)
    {
        $this->_messageStatusRepository = $messageStatusRepository;
    }

    public function handle($userId)
    {
        return $this->_messageStatusRepository->numOfUnreadMessages($userId);
    }
}