<?php

namespace mc;

use mc\ParticipantResult;

class ContestTable
{
    private $result = [];

    public function __construct(array $participants, array $tasks)
    {
        foreach ($participants as $participant) {
            $result[$participant->name()] = new ParticipantResult($tasks);
        }
    }

    public function participantResult($participantName)
    {
        return $this->result[$participantName];
    }
}
