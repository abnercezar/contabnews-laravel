<?php

namespace App\Actions;

use App\Models\Classified;

class UpdateClassifiedAction
{
    public function execute(Classified $classified, array $data): Classified
    {
        $classified->update($data);
        return $classified;
    }
}
