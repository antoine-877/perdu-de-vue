<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $validated = $request->validate([
            'photos' => ['required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['required', 'image', 'max:5120'],
        ]);

        foreach ($validated['photos'] as $file) {
            $path = $file->store('items', 'public');
            $hasCoverRecord = $item->photos()->where('is_cover', true)->exists();

            if (! $hasCoverRecord && $item->photo_path) {
                $item->photos()->create(['path' => $item->photo_path, 'is_cover' => true]);
                $hasCoverRecord = true;
            }

            $isCover = ! $hasCoverRecord;

            $item->photos()->create(['path' => $path, 'is_cover' => $isCover]);

            if ($isCover) {
                $item->update(['photo_path' => $path]);
            }
        }

        return redirect()->route('items.show', $item)->with('success', __('site.photos_uploaded'));
    }

    public function cover(Item $item, Photo $photo)
    {
        abort_unless($photo->item_id === $item->id, 404);

        $item->photos()->update(['is_cover' => false]);
        $photo->update(['is_cover' => true]);
        $item->update(['photo_path' => $photo->path]);

        return redirect()->route('items.show', $item)->with('success', __('site.cover_updated'));
    }

    public function destroy(Item $item, Photo $photo)
    {
        abort_unless($photo->item_id === $item->id, 404);

        $wasCover = $photo->is_cover || $item->photo_path === $photo->path;
        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        if ($wasCover) {
            $nextCover = $item->photos()->oldest()->first();
            $nextCover?->update(['is_cover' => true]);
            $item->update(['photo_path' => $nextCover?->path]);
        }

        return redirect()->route('items.show', $item)->with('success', __('site.photo_deleted'));
    }
}
