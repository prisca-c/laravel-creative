<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use LaravelIdea\Helper\App\Models\_IH_Article_C;

class ArticleController extends Controller
{
    private Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function index(): array
    {
        return $this->article->all()->toArray();
    }

    public function show(Request $request): Collection
    {
        return Article::find($request->id);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required', 'max:255',
            'description' => 'required', 'max:255',
            'content' => 'required', 'max:255',
            'user_id' => 'required',
            'published_at' => 'date',
            'tags' => 'exists:tags,id'
        ]);

        if ($validated) {
            $this->article->title = $request->input('title');
            $this->article->description = $request->input('description');
            $this->article->content = $request->input('content');
            $this->article->user_id = $request->input('user_id');
            $this->article->published_at = $request->input('published_at');
            $this->article->save();

            $this->article->tags()->attach($request->input('tags'));

            return response()->json([
                'message' => 'Article created successfully',
                'article' => $this->article
            ], 201);
        } else {
            return redirect()->back()->withErrors($validated);
        }
    }

    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required', 'max:255',
            'description' => 'required', 'max:255',
            'content' => 'required', 'max:255',
            'user_id' => 'required',
            'tags' => 'exists:tags,id',
            'published_at' => 'date'
        ]);

        if ($validated) {
            $article = Article::find($request->id);
            $article->title = $request->input('title');
            $article->description = $request->input('description');
            $article->content = $request->input('content');
            $article->user_id = $request->user()->id;
            $article->published_at = $request->input('published_at');
            $article->save();

            $article->tags()->sync($request->input('tags'));

            return response()->json([
                'message' => 'Article updated successfully',
                'article' => $article
            ], 201);
        } else {
            return redirect()->back()->withErrors($validated);
        }
    }

    public function destroy(Request $request)
    {
        $article = Article::find($request->id);
        $article->delete();
    }

    public function published(): \Inertia\Response
    {
        return Inertia::render('Dashboard',[
            'admin' => Auth::user()->admin(),
            'articles' => Article::with('user', 'tags', 'comments')
                ->orderByDesc('published_at')
                ->whereNotNull('published_at')
                ->whereDate('published_at', '<=', now())
                ->get(),
            'tags'=> Tag::all(),
        ]);
    }

    public function articlesWithRelations(): array
    {
        return Article::with('comments', 'tags', 'user')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

    public function user(Request $request): array
    {
        return Article::where('user_id', $request->user()->id)
            ->with('comments', 'tags', 'user')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

    public function tag(Request $request): array
    {
        return $this->article->whereHas('tags', function ($query) use ($request) {
            $query->where('id', $request->tag);
        })->with('comments', 'tags', 'user')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

}
