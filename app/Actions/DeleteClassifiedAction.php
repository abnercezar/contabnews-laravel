<?php

namespace App\Actions;

use App\Models\Classified;

class DeleteClassifiedAction
{
    public function execute(Classified $classified): bool
    {
        return $classified->delete();
    }
}
