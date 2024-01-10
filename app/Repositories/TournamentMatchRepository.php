<?php

namespace App\Repositories;

use App\Interfaces\TournamentMatchInterface;
use App\Models\TournamentMatch;

class TournamentMatchRepository implements TournamentMatchInterface
{
    public function deleteAll()
    {
        return TournamentMatch::query()->delete();
    }

    public function getLastMatch()
    {
        return TournamentMatch::orderBy('id', 'DESC')->first();
    }

    public function create(array $data)
    {
        return TournamentMatch::create($data);
    }
}
