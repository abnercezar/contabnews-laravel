<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassifiedRequest;
use App\Http\Requests\UpdateClassifiedRequest;
use App\Models\Classified;
use Illuminate\Http\Request;
use App\Actions\CreateClassifiedAction;
use App\Actions\UpdateClassifiedAction;
use App\Actions\DeleteClassifiedAction;

class ClassifiedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(StoreClassifiedRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $action = app(CreateClassifiedAction::class);
        $classified = $action->execute($data, $user);

        $payload = $classified->toArray();
        $payload['user'] = $user ? $user->only(['id', 'name']) : null;

        return response()->json($payload, 201);
    }

    public function update(UpdateClassifiedRequest $request, Classified $classified)
    {
        $user = $request->user();
        if (!$user || $classified->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validated();
        $action = app(UpdateClassifiedAction::class);
        $classified = $action->execute($classified, $data);

        $payload = $classified->toArray();
        $payload['user'] = $user ? $user->only(['id', 'name']) : null;

        return response()->json($payload, 200);
    }

    public function destroy(Request $request, Classified $classified)
    {
        $user = $request->user();
        if (!$user || $classified->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $action = app(DeleteClassifiedAction::class);
        $action->execute($classified);

        return response()->json(null, 204);
    }
}
