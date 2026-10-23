 @extends('layouts.app')

 @section('title', __('site.items_title'))

 @section('content')
     <div class="container">
         <header class="page-header">
             <h1>{{ __('site.items_title') }}</h1>

             <a href="{{ route('items.create') }}" class="btn btn-primary">
                 {{ __('site.add_item') }}
             </a>
         </header>

         @if (session('success'))
             <div class="alert alert-success">
                 {{ session('success') }}
             </div>
         @endif

         <form action="{{ route('items.index') }}" method="GET"
             class="mb-6 flex flex-wrap items-end gap-4 rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
             <div class="min-w-48 flex-1">
                 <x-label for="q">{{ __('site.search') }}</x-label>
                 <x-input name="q" id="q" :value="request('q')" type="search"
                     placeholder="{{ __('site.item_name') }}" />
             </div>
             <div class="min-w-48 flex-1">
                 <x-label for="lieu">{{ __('site.location') }}</x-label>
                 <x-select name="lieu" id="lieu">
                     <option value="">{{ __('site.all_locations') }}</option>
                     @foreach ($places as $place)
                         <option value="{{ $place->id }}" @selected((string) request('lieu') === (string) $place->id)>
                             {{ app('translator')->has('site.seeded_places.' . $place->name) ? __('site.seeded_places.' . $place->name) : $place->name }}
                         </option>
                     @endforeach
                 </x-select>
             </div>
             <div class="min-w-40">
                 <x-label for="tri">{{ __('site.sort_by') }}</x-label>
                 <x-select name="tri" id="tri">
                     <option value="date" @selected(request('tri', 'date') === 'date')>{{ __('site.sort_newest') }}</option>
                     <option value="nom" @selected(request('tri') === 'nom')>{{ __('site.sort_name') }}</option>
                 </x-select>
             </div>
             <div class="min-w-48 flex-1">
                 <x-label for="couleur">{{ __('site.color') }}</x-label>
                 <x-select name="couleur" id="couleur">
                     <option value="">{{ __('site.all_colors') }}</option>
                     @foreach ($colors as $color)
                         <option value="{{ $color->value }}" @selected(request('couleur') === $color->value)>
                             {{ __('site.color_' . $color->value) }}
                         </option>
                     @endforeach
                 </x-select>
             </div>
             <button type="submit"
                 class="rounded-lg bg-teal-700 px-4 py-2 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                 {{ __('site.filter') }}
             </button>
             @if (request()->hasAny(['lieu', 'couleur', 'q', 'tri']))
                 <a href="{{ route('items.index') }}"
                     class="rounded-lg px-4 py-2 font-semibold text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:text-slate-200 dark:hover:bg-slate-800">
                     {{ __('site.clear') }}
                 </a>
             @endif
         </form>

         <div class="card-grid">
             @forelse ($items as $item)
                 <article class="item-card">
                     @if ($item->photo_path)
                         <img src="{{ Storage::disk('public')->url($item->photo_path) }}"
                             alt="{{ __('site.photo_of', ['name' => $item->name]) }}"
                             class="mb-4 aspect-[4/3] w-full rounded-lg object-cover">
                     @endif
                     <h2>
                         <a href="{{ route('items.show', $item) }}">
                             {{ $item->name }}
                         </a>
                     </h2>

                     @if ($item->description)
                         <p>{{ $item->description }}</p>
                     @endif

                     <p>
                         <strong>{{ __('site.location_label') }}</strong>
                         @if ($item->place)
                             <a href="{{ route('places.show', $item->place) }}">
                                 {{ app('translator')->has('site.seeded_places.' . $item->place->name) ? __('site.seeded_places.' . $item->place->name) : $item->place->name }}
                             </a>
                         @else
                             {{ __('site.unknown_location') }}
                         @endif
                     </p>

                     <p>
                         <strong>{{ __('site.color_label') }}</strong>
                         <x-color-badge :color="$item->color" />
                     </p>

                     <p>
                         <strong>{{ __('site.found_date_label') }}</strong>
                         @if ($item->found_on)
                             <time datetime="{{ $item->found_on->toDateString() }}"
                                 title="{{ $item->found_on->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}">
                                 {{ $item->found_on->locale(app()->getLocale())->diffForHumans() }}
                             </time>
                         @else
                             {{ __('site.not_specified') }}
                         @endif
                     </p>

                     <p>
                         <strong>{{ __('site.status_label') }}</strong>
                         {{ $item->isReturned() ? __('site.returned') : __('site.not_returned') }}
                     </p>

                     @if ($item->returned_at)
                         <p>
                             <strong>{{ __('site.returned_date_label') }}</strong>
                             {{ $item->returned_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}
                         </p>
                     @endif

                     @if ($item->tags->isNotEmpty())
                         <div class="mt-3">
                             <strong>{{ __('site.tags_label') }}</strong>

                             <div class="mt-2 flex flex-wrap gap-2">
                                 @foreach ($item->tags as $tag)
                                     <a href="{{ route('tags.show', $tag) }}"
                                         class="inline-block rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                                         <x-tag-badge :tag="$tag" />
                                     </a>
                                 @endforeach
                             </div>
                         </div>
                     @endif

                     <div class="item-actions">
                         <a href="{{ route('items.show', $item) }}" class="btn">
                             {{ __('site.view') }}
                         </a>

                         <a href="{{ route('items.edit', $item) }}" class="btn">
                             {{ __('site.edit') }}
                         </a>

                         <form action="{{ route('items.destroy', $item) }}" method="POST"
                             onsubmit="return confirm(@json(__('site.delete_item_confirm')));">
                             @csrf
                             @method('DELETE')

                             <button type="submit" class="btn btn-danger">
                                 {{ __('site.delete') }}
                             </button>
                         </form>
                     </div>
                 </article>
             @empty
                 <p>{{ __('site.no_items') }}</p>
             @endforelse
         </div>

         <div class="pagination">
             {{ $items->links() }}
         </div>
     </div>
 @endsection
