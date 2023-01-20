<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    private Tag $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function index(): array
    {
        return DB::table('tags')
            ->orderByDesc('created_at')
            ->get()->toArray();
    }

    public function show(Request $request): Collection
    {
        return TagController::find($request->id);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required', 'max:255',
        ]);

        if ($validated) {
            $this->tag->name = $request->input('name');
            $this->tag->save();

            return response()->json([
                'message' => 'TagController created successfully',
                'tag' => $this->tag
            ], 201);
        } else {
            return redirect()->back()->withErrors($validated);
        }
    }

}
