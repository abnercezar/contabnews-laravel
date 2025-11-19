<?php

namespace App\Actions;

use App\Models\Classified;
use App\Models\User;

class CreateClassifiedAction
{
    public function execute(array $data, ?User $author = null): Classified
    {
        if ($author) {
            $data['author'] = $author->name;
            $data['user_id'] = $author->id;
        }

        return Classified::create($data);
    }
}
