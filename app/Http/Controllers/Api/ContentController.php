<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\StoreContentRequest;
use App\Http\Requests\Content\UpdateContentRequest;
use App\Models\Content;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Content::query();

        $with = array_filter(array_map('trim', explode(',', (string) $request->query('with', ''))));
        $allowedWith = ['class', 'lesson'];
        $with = array_values(array_intersect($with, $allowedWith));
        if ($with) {
            $query->with($with);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->string('class_id'));
        }

        if ($request->filled('lesson_id')) {
            $query->where('lesson_id', $request->string('lesson_id'));
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->string('subject'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('language')) {
            $query->where('language', $request->string('language'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $content = $query->latest()->paginate($perPage);

        return response()->json($content);
    }

    public function store(StoreContentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['language'] = $data['language'] ?? 'Gojri';
        $data['status'] = $data['status'] ?? true;

        $content = Content::create($data);

        return response()->json($content, 201);
    }

    public function show(Content $content): JsonResponse
    {
        return response()->json($content);
    }

    public function update(UpdateContentRequest $request, Content $content): JsonResponse
    {
        $content->fill($request->validated())->save();

        return response()->json($content);
    }

    public function destroy(Content $content): JsonResponse
    {
        $content->delete();

        return response()->json(null, 204);
    }
}
