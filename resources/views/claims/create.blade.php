@extends('layouts.app')

@section('title', __('site.report_title'))

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1>{{ __('site.report_title') }}</h1>
        <p class="mb-6 text-slate-600 dark:text-slate-300">{{ __('site.report_intro') }}</p>

        @if ($errors->any())
            <div role="alert"
                class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('claims.store') }}"
            class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
            @csrf
            <div>
                <x-label for="claimant_name">{{ __('site.name') }}</x-label>
                <x-input name="claimant_name" id="claimant_name" :value="old('claimant_name')" required maxlength="120"
                    autocomplete="name" />
                @error('claimant_name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <x-label for="email">{{ __('site.email') }}</x-label>
                <x-input type="email" name="email" id="email" :value="old('email')" required maxlength="255"
                    autocomplete="email" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <x-label for="description">{{ __('site.lost_description') }}</x-label>
                <x-textarea name="description" id="description" rows="5" maxlength="2000"
                    required>{{ old('description') }}</x-textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">{{ __('site.submit_report') }}</button>
        </form>
    </div>
@endsection
