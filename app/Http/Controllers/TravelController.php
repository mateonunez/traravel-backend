<?php

namespace App\Http\Controllers;

use App\Lib\Message;
use App\Models\Mood;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TravelController extends Controller
{
    /**
     * @var \App\Models\Travel
     */
    protected $model = Travel::class;

    /**
     * Index
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = is_null(auth()->user())
                ? auth('api')->user()
                : auth()->user();

            $travels = $this->model::with([
                'moods',
                'tours' => fn ($q) => $q->orderBy('startingDate', 'asc')
            ])
                ->where('isPublic', true);

            if (!$user || !$user?->isEditor()) {
                $travels = $travels->where('isPublic', true);
            }


            $travels = $travels
                ->get()
                ->toArray();

            return $this->sendResponse($travels, Message::INDEX_OK);
        } catch (\Exception $e) {
            // TODO Add log here
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Show API
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $slugOrId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $slugOrId): JsonResponse
    {
        try {
            $user = is_null(auth()->user())
                ? auth('api')->user()
                : auth()->user();

            $travel = $this->model::with([
                'moods',
                'tours' => fn ($q) => $q->orderBy('startingDate', 'asc')
            ])
                ->where('slug', $slugOrId)
                ->orWhere('id', $slugOrId)
                ->first();

            if (!$user || !$user?->isEditor() && !$travel->isPublic) {
                return $this->sendNotFound();
            }

            return $this->sendResponse($travel->toArray(), Message::SHOW_OK);
        } catch (\Exception $e) {
            // TODO Add log here
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store API
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $payload = $request->all();

            $moods = $payload['moods'] ?? Mood::all()->toArray();
            $tours = $payload['tours'] ?? [];

            // computing number of days before creating travel

            $numberOfDays = 0;
            foreach ($tours as $tour) {
                $numberOfDays += Travel::computeNumberOfDays($tour['startingDate'], $tour['endingDate']);
                if ($numberOfDays < 0) {
                    return $this->sendError(Message::INVALID_DATE);
                }
            }

            $payload['numberOfDays'] = $numberOfDays;


            $travel = $this->model::create($payload);

            // set moods for travel
            foreach ($moods as $mood) {
                $travel->moods()->attach($mood['id'], [
                    'rating' => rand(1, 100)
                ]);
            }

            // creating tours
            foreach ($tours as $tour) {
                $tour['travelId'] = $travel->id;
                Tour::create($tour);
            }

            $travel = $this->model::with(['moods', 'tours'])
                ->where('id', $travel->id)
                ->first();

            return $this->sendResponse($travel->toArray(), Message::CREATE_OK);
        } catch (\Exception $ex) {
            // TODO Add log here
            return $this->sendError($ex->getMessage());
        }
    }
}
