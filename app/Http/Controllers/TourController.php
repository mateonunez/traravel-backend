<?php

namespace App\Http\Controllers;

use App\Lib\Message;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TourController extends Controller
{
    /**
     * @var \App\Models\Tour
     */
    protected $model = Tour::class;

    /**
     * Store method
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->store($request);
    }

    /**
     * Edit method
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // TODO Add validation
            $payload = $request->all();

            $tour = $this->model::find($id);

            if (!$tour) {
                return $this->sendError(Message::NOT_FOUND);
            }

            $tour->update($payload);

            $travel = $tour->travel;

            $numberOfDays = Travel::computeNumberOfDays($payload['startingDate'], $payload['endingDate']);
            if ($numberOfDays < 0) {
                return $this->sendError(Message::INVALID_DATE);
            }

            $travel->update([
                'numberOfDays' => $numberOfDays
            ]);

            return $this->sendResponse(
                $tour->fresh()->toArray(),
                Message::UPDATE_OK
            );
        } catch (\Exception $ex) {
            // TODO Add log here
            return $this->sendError($ex->getMessage());
        }
    }
}
