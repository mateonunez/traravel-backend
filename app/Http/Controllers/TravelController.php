<?php

namespace App\Http\Controllers;

use App\Lib\Message;
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

            $travels = $this->model::with('moods');

            if (!$user || !$user?->isEditor()) {
                $travels = $travels->where('isPublic', true);
            }

            $travels = $travels->get()->toArray();

            return $this->sendResponse($travels, Message::INDEX_OK);
        } catch (\Exception $e) {
            // TODO Add log here
            return $this->sendError($e->getMessage());
        }
    }
}
