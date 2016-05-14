<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 15:37
 */

namespace Tzookb\TBMsg\Domain\Entities;


use Carbon\Carbon;
use Tzookb\TBMsg\Domain\Exceptions\NotEnoughParticipantsForConversationException;

class Conversation
{
    private $_id;
    private $_title;

    private $_participants;

    /** @var Message[] */
    private $_messages;

    /** @var Carbon */
    private $_created;

    public function __construct(array $participants, array $messages = [], $title = '', $created = null, $id = null)
    {
        $this->setParticipants($participants);

        $this->_messages = $messages;

        if (is_null($created)) {
            $created = Carbon::now();
        }
        $this->setCreated($created);

        $this->_title = $title;

        $this->_id = $id;
    }

    public function getId() {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    public function getParticipants() {
        return $this->_participants;
    }

    public function getMessages() {
        return $this->_messages;
    }

    public function getCreated() {
        return $this->_created;
    }

    /**
     * @param array $participants
     * @throws NotEnoughParticipantsForConversationException
     */
    public function setParticipants($participants)
    {
        if (sizeof($participants) < 1) {
            throw new NotEnoughParticipantsForConversationException;
        }
        $this->_participants = $participants;
    }

    /**
     * @param Carbon $created
     */
    public function setCreated(Carbon $created)
    {
        $this->_created = $created;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

}