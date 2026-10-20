@extends('layouts.app')

@section('title', 'Manage Shows | CineBook')

@section('content')
    <div class="space-y-8">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-red-500">
                    Administration
                </p>

                <h1 class="mt-2 text-3xl font-black text-white sm:text-4xl">
                    Manage Shows
                </h1>

                <p class="mt-2 text-slate-400">
                    View and search scheduled movie shows.
                </p>
            </div>

            <a href="{{ route('shows.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">
                + Schedule Show
            </a>
        </div>

        {{-- Search and Filter --}}
        <form action="{{ route('admin.shows.index') }}" method="GET"
              class="grid gap-4 rounded-2xl border border-white/10 bg-slate-900 p-5 sm:grid-cols-3">

            <div class="sm:col-span-2">
                <label for="search" class="mb-2 block text-sm font-semibold text-slate-300">
                    Search Movie
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Enter movie title"
                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-red-500"
                >
            </div>

            <div>
                <label for="timing" class="mb-2 block text-sm font-semibold text-slate-300">
                    Show Timing
                </label>

                <select
                    id="timing"
                    name="timing"
                    class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-red-500"
                >
                    <option value="">All Shows</option>
                    <option value="upcoming" @selected(request('timing') === 'upcoming')>
                        Upcoming
                    </option>
                    <option value="past" @selected(request('timing') === 'past')>
                        Past
                    </option>
                </select>
            </div>

            <div class="flex flex-wrap gap-3 sm:col-span-3">
                <button type="submit"
                        class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">
                    Search Shows
                </button>

                <a href="{{ route('admin.shows.index') }}"
                   class="rounded-xl border border-white/10 px-5 py-3 font-semibold text-slate-200 transition hover:bg-white/5">
                    Reset
                </a>
            </div>
        </form>

        {{-- Shows Table --}}
        <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-900">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-b border-white/10 bg-white/5 text-slate-400">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Movie</th>
                        <th class="px-5 py-4 font-semibold">Theatre</th>
                        <th class="px-5 py-4 font-semibold">Screen</th>
                        <th class="px-5 py-4 font-semibold">Date & Time</th>
                        <th class="px-5 py-4 font-semibold">Ticket Price</th>
                        <th class="px-5 py-4 font-semibold">Timing</th>
                        <th class="px-5 py-4 font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/5">
                    @forelse ($shows as $show)
                        <tr class="text-slate-300">
                        
<td class="px-5 py-4 font-semibold text-white">
    {{ $show->movie->title ?? 'Unavailable' }}
</td>

<td class="px-5 py-4">
    {{ $show->screen->theatre->name ?? 'Unavailable' }}
</td>

<td class="px-5 py-4">
    {{ $show->screen->name ?? 'Unavailable' }}
</td>

<td class="px-5 py-4">
    {{ $show->start_time->format('d M Y, h:i A') }}
</td>

<td class="px-5 py-4 font-semibold text-white">
    ₹{{ number_format($show->ticket_price, 2) }}
</td>

<td class="px-5 py-4">
    @if ($show->start_time->isFuture())
        <span class="rounded-full bg-green-500/10 px-3 py-1 text-xs font-semibold text-green-400">
            Upcoming
        </span>
    @else
        <span class="rounded-full bg-slate-700 px-3 py-1 text-xs font-semibold text-slate-300">
            Past
        </span>
    @endif
</td>

<td class="px-5 py-4">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.shows.edit', $show) }}"
           class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-500">
            Edit
        </a>

        <form action="{{ route('admin.shows.destroy', $show) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this show?');">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-500">
                Delete
            </button>
        </form>
    </div>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                No shows found. Try changing your search or filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div>
            {{ $shows->links() }}
        </div>

        {{-- Back Link --}}
        <a href="{{ route('admin.dashboard') }}"
           class="inline-block text-sm font-semibold text-red-400 transition hover:text-red-300">
            ← Back to Admin Dashboard
        </a>

    </div>
@endsection
