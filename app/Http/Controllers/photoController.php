<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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

public function destroy(Photos $photo)
    {
    // Apenas fotógrafos podem excluir
    if (auth()->user()->role !== 'fotografo') {
        abort(403, 'Você não tem permissão para excluir fotos.');
    }

    // O fotógrafo só pode excluir a própria foto
    if ($photo->user_id !== auth()->id()) {
        abort(403, 'Você não pode excluir esta foto.');
    }

    // Exclui o arquivo físico da foto
    if ($photo->image_path) {
        Storage::disk('public')->delete($photo->image_path);
    }

    // Exclui o registro do banco
    $photo->delete();

    return redirect()
        ->route('dashboard')
        ->with('success', 'Foto excluída com sucesso!');
}

public function edit(Photos $photo)
{
    if (auth()->user()->role !== 'fotografo') {
        abort(403);
    }

    if ($photo->user_id !== auth()->id()) {
        abort(403);
    }

    return view('layouts.edit', compact('photo'));
}

public function update(Request $request, Photos $photo)
{
    // Apenas fotógrafos
    if (auth()->user()->role !== 'fotografo') {
        abort(403);
    }

    // Apenas o dono da foto
    if ($photo->user_id !== auth()->id()) {
        abort(403);
    }

    $request->validate([
        'title' => 'nullable|string|max:255',
        'photo' => 'nullable|image|max:10240',
    ]);

    $photo->title = $request->title;

    // Se o fotógrafo escolheu uma nova imagem
    if ($request->hasFile('photo')) {

        // Remove a imagem antiga
        if ($photo->image_path) {
            Storage::disk('public')->delete($photo->image_path);
        }

        // Salva a nova imagem
        $photo->image_path = $request->file('photo')
            ->store('photos', 'public');
    }

    $photo->save();

    return redirect()
        ->route('dashboard')
        ->with('success', 'Foto atualizada com sucesso!');
}


}


