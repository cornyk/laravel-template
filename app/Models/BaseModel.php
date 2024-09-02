<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;

    protected $connection = 'default';

    protected $table = '';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $dateFormat = 'U';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected function serializeDate(DateTimeInterface $date): int|string
    {
        return $date->getTimestamp();
    }
}
