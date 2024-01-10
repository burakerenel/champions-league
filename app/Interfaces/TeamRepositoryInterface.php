<?php

namespace App\Interfaces;

interface TeamRepositoryInterface
{
    public function deleteAll();
    public function getAll();

    public function findById(int $id);
}
