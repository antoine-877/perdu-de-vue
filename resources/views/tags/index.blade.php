 @extends('layouts.app')

 @section('title', __('site.tags_title'))

 @section('content')
     <div class="container">
         <header class="page-header">
             <h1>{{ __('site.tags_title') }}</h1>

             <a href="{{ route('tags.create') }}" class="btn btn-primary">
                 {{ __('site.create_tag') }}
             </a>
         </header>

         @if (session('success'))
             <div class="alert alert-success">
                 {{ session('success') }}
             </div>
         @endif

         <div class="card-grid">
             @forelse ($tags as $tag)
                 <article class="tag-card">
                     <h2>
                         <a href="{{ route('tags.show', $tag) }}" class="inline-block">
                             <x-tag-badge :tag="$tag" />
                         </a>
                     </h2>

                     <p>
                         <strong>{{ __('site.associated_items_count') }}</strong>
                         {{ $tag->items_count ?? $tag->items->count() }}
                     </p>

                     <div class="tag-actions">
                         <a href="{{ route('tags.show', $tag) }}" class="btn">
                             {{ __('site.view') }}
                         </a>

                         <a href="{{ route('tags.edit', $tag) }}" class="btn btn-primary">
                             {{ __('site.edit') }}
                         </a>

                         <form action="{{ route('tags.destroy', $tag) }}" method="POST"
                             onsubmit="return confirm(@json(__('site.delete_tag_confirm')));">
                             @csrf
                             @method('DELETE')

                             <button type="submit" class="btn btn-danger">
                                 {{ __('site.delete') }}
                             </button>
                         </form>
                     </div>
                 </article>
             @empty
                 <p>{{ __('site.no_tags_yet') }}</p>
             @endforelse
         </div>

         <div class="pagination">
             {{ $tags->links() }}
         </div>
     </div>
 @endsection
