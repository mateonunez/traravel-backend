<?php

namespace App\Http\Controllers;

use App\Lib\Message;
use App\Models\Mood;
use App\Models\Tour;
use App\Models\User;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Search API
     *
     * ! The simplest and dumbest search API ever.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $results = [];

            $user = is_null(auth()->user())
                ? auth('api')->user()
                : auth()->user();


            $query = $request->query();

            $type = $query['type'] ?? null;
            $q = $query['q'] ?? null;

            if (!$q) {
                return $this->sendResponse([], Message::NO_QUERY_PROVIDED);
            }

            if (!$type) {
                $travels = Travel::with([
                    'moods',
                    'tours' => fn ($q) => $q->orderBy('startingDate', 'asc')
                ])
                    ->whereRaw("(LOWER(name) LIKE '%" . strtolower($q) . "%' OR LOWER(description) LIKE '%" . strtolower($q) . "%') AND isPublic = 1")
                    ->limit(3)
                    ->get()
                    ->toArray();

                $tours = Tour::with(['travel'])
                    ->whereHas('travel', fn ($q) => $q->where('isPublic', true))
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orderBy('startingDate', 'asc')
                    ->limit(3)
                    ->get()
                    ->toArray();

                if ($user && $user->isAdmin()) {
                    $users = User::where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->limit(3)
                        ->get()
                        ->toArray();
                }

                $results = [
                    'travels' => $travels,
                    'tours' => $tours,
                    'users' => $users ?? [],
                ];
            } else {
                switch ($type) {
                    case 'travels':
                        $travels = Travel::with([
                            'moods',
                            'tours' => fn ($q) => $q->orderBy('startingDate', 'asc')
                        ])
                            ->whereRaw("(LOWER(name) LIKE '%" . strtolower($q) . "%' OR LOWER(description) LIKE '%" . strtolower($q) . "%') AND isPublic = 1")
                            ->limit(3)
                            ->get()
                            ->toArray();

                        $results = ['travels' => $travels];
                        break;

                    case 'tours':
                        $tours = Tour::with(['travel'])
                            ->whereHas('travel', fn ($q) => $q->where('isPublic', true))
                            ->where('name', 'like', "%{$q}%")
                            ->orWhere('description', 'like', "%{$q}%")
                            ->limit(3)
                            ->get()
                            ->toArray();

                        $results = ['tours' => $tours];
                        break;

                    case 'users':
                        $users = User::where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->limit(3)
                            ->get()
                            ->toArray();

                        $results = ['users' => $users];
                        break;

                    default:
                        break;
                }
            }

            return $this->sendResponse($results, Message::INDEX_OK);
        } catch (\Exception $e) {
            // TODO Add log here
            return $this->sendError($e->getMessage());
        }
    }
}
