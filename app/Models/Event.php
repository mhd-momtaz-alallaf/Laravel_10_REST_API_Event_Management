<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // User is the ouner of Events. (one user => many events)
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class); // Event is the ouner of the attendees(the parent). (one event => many attendees)
    }
}
