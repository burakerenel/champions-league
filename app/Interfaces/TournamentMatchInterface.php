<?php

namespace App\Interfaces;

interface TournamentMatchInterface
{
    public function deleteAll();

    public function getLastMatch();

    public function create(array $data);

}
