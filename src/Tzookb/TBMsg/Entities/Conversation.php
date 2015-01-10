<?php
/**
 * This awesome file created by:
 * tzookb
 * Enjoy!
 * Date: 6/11/14
 */

namespace Tzookb\TBMsg\Entities;


use Illuminate\Support\Collection;

class Conversation {

    protected $id;
    protected $participants;
    protected $messages;
    protected $created;

    protected $type;

    const GROUP = 'group';
    const COUPLE = 'couple';


    function __construct()
    {
        $this->participants = [];
        $this->messages = new Collection();
    }

    function addParticipant($participant)
    {
        $this->participants[$participant] = true;
    }

    function removeParticipant($participant)
    {
        unset( $this->participants[$participant] );
    }

    function addMessage(Message $msg)
    {
        $this->addParticipant( $msg->getSender() );
        $this->messages[ $msg->getId() ] = $msg;
    }

    function getNumOfParticipants()
    {
        return count( $this->participants );
    }

    function getNumOfMessages()
    {
        return count( $this->messages );
    }

    function getAllParticipants()
    {
        return array_keys( $this->participants );
    }

    function getTheOtherParticipant($me)
    {
        $participants = $this->participants;
        unset($participants[$me]);
        $participants = array_keys($participants);
        return array_pop($participants);
    }

    function getAllMessages()
    {
        return $this->messages;
    }
    
    function getFirstMessage()
    {
        return $this->messages->first();
    }

    /**
     * @return Message
     */
    function getLastMessage()
    {
        return $this->messages->last();
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getType() {
        if ( $this->getNumOfParticipants() > 2 )
            return self::GROUP;
        return self::COUPLE;
    }

}
