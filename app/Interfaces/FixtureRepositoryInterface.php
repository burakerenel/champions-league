<?php

namespace App\Interfaces;

interface FixtureRepositoryInterface
{
    public function deleteAll();
    public function getAll();
    public function create(array $data);

    public function getWeekFixture(int $week);
}
