@extends('layouts.app')

@section('title', __('site.statistics'))

@section('content')
    <div class="space-y-10">
        <h1>{{ __('site.statistics') }}</h1>
        <p class="text-sm text-slate-600 dark:text-slate-300">{{ __('site.statistics_intro') }}</p>

        <section aria-labelledby="place-statistics">
            <h2 id="place-statistics" class="text-xl font-bold">{{ __('site.items_by_place') }}</h2>
            @forelse ($byPlace as $row)
                <div class="mb-4">
                    <div class="mb-1 flex justify-between gap-4">
                        <span>{{ app('translator')->has('site.seeded_places.' . $row->name) ? __('site.seeded_places.' . $row->name) : $row->name }}</span><span>{{ $row->total }}</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded bg-slate-200 dark:bg-slate-700" role="meter"
                        aria-label="{{ $row->name }}" aria-valuenow="{{ $row->total }}" aria-valuemin="0"
                        aria-valuemax="{{ $maxPlace }}">
                        <div class="h-full rounded bg-teal-600 dark:bg-teal-400"
                            style="width: {{ ($row->total / $maxPlace) * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p>{{ __('site.no_places') }}</p>
            @endforelse
        </section>

        <section aria-labelledby="month-statistics">
            <h2 id="month-statistics" class="text-xl font-bold">{{ __('site.items_by_month') }}</h2>
            @forelse ($byMonth as $row)
                <div class="mb-4">
                    <div class="mb-1 flex justify-between gap-4">
                        <span>{{ \Carbon\Carbon::createFromFormat('!Y-m', $row->month)->locale(app()->getLocale())->isoFormat('MMMM YYYY') }}</span><span>{{ $row->total }}</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded bg-slate-200 dark:bg-slate-700" role="meter"
                        aria-label="{{ $row->month }}" aria-valuenow="{{ $row->total }}" aria-valuemin="0"
                        aria-valuemax="{{ $maxMonth }}">
                        <div class="h-full rounded bg-sky-600 dark:bg-sky-400"
                            style="width: {{ ($row->total / $maxMonth) * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p>{{ __('site.no_statistics_items') }}</p>
            @endforelse
        </section>
    </div>
@endsection
