<?php

namespace App\Observers;

use App\Models\Travel;
use Illuminate\Support\Str;

class TravelObserver
{
    /**
     * Handle the travel "creating" event.
     *
     * @param \App\Models\Travel $travel
     *
     * @return void
     */
    public function creating(Travel $travel)
    {
        if (empty($travel->slug)) {
            $travel->slug = Str::slug(Str::lower($travel->name));
        }

        $travel->numberOfNights = $travel->numberOfDays > 0
            ? $travel->numberOfDays - 1
            : 0;
    }

    /**
     * Handle the travel "updating" event.
     *
     * @param \App\Models\Travel $travel
     *
     * @return void
     */
    public function updating(Travel $travel)
    {
        if (empty($travel->slug)) {
            $travel->slug = Str::slug(Str::lower($travel->name));
        }

        $travel->numberOfNights = $travel->numberOfDays !== 0
            ? $travel->numberOfDays - 1
            : 0;
    }
}
