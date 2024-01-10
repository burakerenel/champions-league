<?php

namespace App\Repositories;

use App\Interfaces\TeamRepositoryInterface;
use App\Models\Team;

class TeamRepository implements TeamRepositoryInterface
{

    public function deleteAll(){
        return Team::query()->delete();
    }

    public function getAll(){
        return Team::all();
    }


    public function findById(int $id){
        return Team::find($id);
    }
}
