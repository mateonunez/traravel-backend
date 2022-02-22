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

            $travels = $this->model::with(['moods', 'tours']);

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

    /**
     * Search API
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $travels = $this->model::with('moods')
                ->where('isPublic', true)
                ->limit(3);

            $query = $request->query();

            // search criteria

            $travels = $travels->get()->toArray();

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

            $travel = $this->model::with(['moods', 'tours'])
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
}
