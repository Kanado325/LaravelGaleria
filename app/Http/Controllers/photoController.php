<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photos;
use App\Models\Like;

class photoController extends Controller
{
    
    public function index() {
        $photos = Photos::with('user')
        ->withCount('likes')
        ->latest()
        ->paginate(9);
        return view('dashboard', compact('photos'));
    }

public function store(Request $request)
    {
        if (auth()->user()->role !== 'fotografo') {
            abort(403, 'Apenas fotógrafos podem publicar fotos.');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'photo' => 'required|image|max:10240',
        ]);

        $path = null;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
        }

        auth()->user()->photos()->create([
            'title' => $request->title,
            'image_path' => $path,
        ]);

        return back()->with('success', 'Foto salva com sucesso!');
    }
    

public function like($photoId)
    {
    $photo = Photos::findOrFail($photoId);

    $like = Like::where('user_id', auth()->id())
                ->where('photo_id', $photo->id)
                ->first();

    if ($like) {
        $like->delete();

        return back()->with('success', 'Curtida removida!');
    }

    Like::create([
        'user_id' => auth()->id(),
        'photo_id' => $photo->id,
    ]);

    return back()->with('success', 'Foto curtida!');
    }
}


