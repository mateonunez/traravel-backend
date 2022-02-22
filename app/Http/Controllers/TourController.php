<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    /**
     * @var \App\Models\Tour
     */
    protected $model = Tour::class;
}
