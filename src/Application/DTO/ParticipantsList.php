<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 13:40
 */

namespace Tzookb\TBMsg\Application\DTO;


class ParticipantsList
{
    /**
     * @var array
     */
    private $participants;

    /**
     * ParticipantsList constructor.
     * @param array $participants
     */
    public function __construct(array $participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return array
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    public function addParticipant($id) {
        $this->participants[] = $id;
    }

}