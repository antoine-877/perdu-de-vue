 @extends('layouts.app')

 @section('title', __('site.places'))

 @section('content')
     <div class="container">
         <header class="page-header">
             <h1>{{ __('site.places_title') }}</h1>
         </header>

         @if (session('success'))
             <div class="alert alert-success">
                 {{ session('success') }}
             </div>
         @endif

         <div class="card-grid">
             @forelse ($places as $place)
                 <article class="place-card">
                     @if ($place->photo_path)
                         <img src="{{ Storage::disk('public')->url($place->photo_path) }}"
                             alt="{{ __('site.place_photo_of', ['name' => __('site.seeded_places.' . $place->name, [], $place->name)]) }}"
                             class="mb-4 aspect-[4/3] w-full rounded-lg object-cover">
                     @endif
                     <h2>
                         <a href="{{ route('places.show', $place) }}">
                             {{ app('translator')->has('site.seeded_places.' . $place->name) ? __('site.seeded_places.' . $place->name) : $place->name }}
                         </a>
                     </h2>

                     <p>
                         <strong>{{ __('site.associated_items_count') }}</strong>
                         {{ $place->items_count ?? $place->items->count() }}
                     </p>

                     <a href="{{ route('places.show', $place) }}" class="btn">
                         {{ __('site.view_place_items') }}
                     </a>
                 </article>
             @empty
                 <p>{{ __('site.no_places_yet') }}</p>
             @endforelse
         </div>

         <div class="pagination">
             {{ $places->links() }}
         </div>
     </div>
 @endsection
