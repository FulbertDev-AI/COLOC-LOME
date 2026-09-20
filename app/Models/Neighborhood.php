<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Neighborhood extends Model
{
    protected string $table = 'neighborhoods';

    public function all(string $orderBy = 'name ASC'): array
    {
        return parent::all($orderBy);
    }
}
