<?php

namespace App\Http\Controllers\Tags;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tags\Tag;
use ReflectionClass;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function assign(Request $request)
    {
        $request->validate([
            'tag' => 'required_without:new_tag|nullable|numeric',
            'new_tag' => 'required_without:tag|nullable|string|max:70',
            'taggable_type' => 'required|string|max:255',
            'taggable_id' => 'required|numeric',
        ]);

        $taggableType = $request->taggable_type;
        $taggableId = $request->taggable_id;

        $entity = $taggableType::findOrFail($taggableId);

        if($request->tag != '')
        {
            $tag = Tag::findOrFail($request->tag);
            $entity->tags()->attach($tag);
        }
        else
        {
            $tagType = new ReflectionClass($taggableType);
            $tag = Tag::create([
                'name' => $request->new_tag,
                'type' => $tagType->getShortName()
            ]);
            $entity->tags()->attach($tag);
        }

        return back()->with(['alert-success' => 'Tag is assigned successfully.']);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'tag_id' => 'required|numeric',
            'taggable_type' => 'required|string|max:255',
            'taggable_id' => 'required|numeric',
        ]);

        $taggableType = $request->taggable_type;
        $taggableId = $request->taggable_id;

        $tag = Tag::findOrFail($request->tag_id);
        $entity = $taggableType::findOrFail($taggableId);
        $entity->tags()->detach($tag);

        return back()->with(['alert-success' => 'Tag is removed successfully']);
    }
}
