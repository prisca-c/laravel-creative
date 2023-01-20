<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    private Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index(): \Illuminate\Support\Collection
    {
        return DB::table('comments')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required', 'max:255',
            'user_id' => 'required', 'exists:users,id',
            'article_id' => 'required'
        ]);

        if ($validated) {
            $comment = new Comment();
            $comment->content = $request->input('content');
            $comment->user_id = $request->input('user_id');
            $comment->article_id = $request->input('article_id');
            $comment->save();

            return redirect()->back()->with('success', 'Comment created successfully');
        } else {
            return redirect()->back()->withErrors($validated);
        }
    }

    public function show(Request $request): Response
    {
        return Comment::find($request->id);
    }

    public function update(Request $request, Comment $comments): \Illuminate\Http\RedirectResponse|Response
    {
        $validated = $request->validate([
            'content' => 'required', 'max:255',
            'user_id' => 'required', 'exists:users,id',
            'article_id' => 'required'
        ]);

        if ($validated) {
            $comments->content = $request->input('content');
            $comments->user_id = $request->input('user_id');
            $comments->article_id = $request->input('article_id');
            $comments->save();

            return redirect()->back()->with('success', 'Comment updated successfully');
        } else {
            return redirect()->back()->withErrors($validated);
        }
    }

    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        $comment = Comment::find($request->id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}
