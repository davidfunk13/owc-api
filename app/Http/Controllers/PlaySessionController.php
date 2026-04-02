<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaySessionRequest;
use App\Http\Requests\UpdatePlaySessionRequest;
use App\Models\PlaySession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaySessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sessions = $request->user()
            ->playSessions()
            ->withCount('games')
            ->orderByDesc('started_at')
            ->paginate(20);

        return response()->json($sessions);
    }

    public function store(StorePlaySessionRequest $request): JsonResponse
    {
        $session = $request->user()->playSessions()->create([
            'title' => $request->title,
            'notes' => $request->notes,
            'started_at' => $request->started_at ?? now(),
            'ended_at' => $request->ended_at,
        ]);

        return response()->json($session, 201);
    }

    public function show(Request $request, PlaySession $playSession): JsonResponse
    {
        if ($playSession->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $playSession->load(['games' => function ($query) {
            $query->with(['map', 'gameHeroes.hero', 'gameRounds'])
                ->orderBy('played_at');
        }]);
        $playSession->loadCount('games');

        return response()->json($playSession);
    }

    public function update(UpdatePlaySessionRequest $request, PlaySession $playSession): JsonResponse
    {
        if ($playSession->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $playSession->update($request->validated());

        return response()->json($playSession);
    }

    public function destroy(Request $request, PlaySession $playSession): JsonResponse
    {
        if ($playSession->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $playSession->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function end(Request $request, PlaySession $playSession): JsonResponse
    {
        if ($playSession->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $playSession->update(['ended_at' => now()]);

        return response()->json($playSession);
    }
}
