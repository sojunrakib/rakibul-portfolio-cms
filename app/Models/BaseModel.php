<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class BaseModel
{
    protected function db(): \App\Core\Database
    {
        return App::get('db');
    }
}
