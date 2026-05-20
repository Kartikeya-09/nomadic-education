<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parent\StoreParentRequest;
use App\Http\Requests\Parent\UpdateParentRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::role('parent');

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $parents = $query->latest()->paginate($perPage);

        return response()->json($parents);
    }

    public function store(StoreParentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['preferred_language'] = $data['preferred_language'] ?? 'Gojri';
        $data['status'] = $data['status'] ?? true;
        $data['password'] = Hash::make($data['password']);

        $parent = User::create($data);
        $parent->assignRole('parent');

        return response()->json($parent, 201);
    }

    public function show(User $parent): JsonResponse
    {
        if (! $parent->hasRole('parent')) {
            abort(404);
        }

        return response()->json($parent);
    }

    public function update(UpdateParentRequest $request, User $parent): JsonResponse
    {
        if (! $parent->hasRole('parent')) {
            abort(404);
        }

        $data = $request->validated();

        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }

        $parent->fill($data)->save();

        return response()->json($parent);
    }

    public function destroy(User $parent): JsonResponse
    {
        if (! $parent->hasRole('parent')) {
            abort(404);
        }

        $parent->delete();

        return response()->json(null, 204);
    }
}
