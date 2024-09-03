<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $comment = $request->user()->comments()->create($request->validated());
        return new CommentResource($comment);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}