<?php

namespace App\Repositories;

use App\Interfaces\FixtureRepositoryInterface;
use App\Models\Fixture;

class FixtureRepository implements FixtureRepositoryInterface
{
    public function deleteAll()
    {
        return Fixture::query()->delete();
    }

    public function create(array $data)
    {
        return Fixture::create($data);
    }

    public function getAll()
    {
        return Fixture::with(['team1', 'team2'])->get();
    }

    public function getWeekFixture(int $week)
    {
        return Fixture::with(['team1', 'team2'])
            ->where('week', $week)
            ->get();
    }
}
