<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // one user can attend many events.
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class); // the other side of the relation.
    }
}
