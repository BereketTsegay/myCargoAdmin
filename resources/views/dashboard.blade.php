<x-layouts.app :title="__('Dashboard')">
    @php
        $totalContainers = \App\Models\container::count();
        $totalParties = \App\Models\Party::count();
        $openContainers = \App\Models\container::whereNotIn('status', ['arrived','departed'])->count();
        $recentOpen = \App\Models\container::whereNotIn('status', ['arrived','departed'])->with(['transitor','shipping'])->latest('created_at')->limit(6)->get();

        // Counts by status for sparkline / breakdown
        $statusKeys = ['in_transit','arrived','departed','delayed'];
        $statusCounts = [];
        $maxCount = 1;
        foreach ($statusKeys as $st) {
            $count = \App\Models\container::where('status', $st)->count();
            $statusCounts[$st] = $count;
            if ($count > $maxCount) $maxCount = $count;
        }

        // Shipper stats for different timeframes (7 days, 30 days, all)
        $timeframes = [
            '7d' => \Carbon\Carbon::now()->subDays(7),
            '30d' => \Carbon\Carbon::now()->subDays(30),
            'all' => null,
        ];

        $shipperStats = [];
        foreach ($timeframes as $key => $start) {
            $query = \App\Models\container::whereNotNull('shipper_id')->with('shipper');
            if ($start) {
                $query->where('created_at', '>=', $start);
            }
            $containersForTime = $query->get();

            $group = [];
            foreach ($containersForTime as $c) {
                if (! $c->shipper) continue;
                $sid = $c->shipper->id;
                if (! isset($group[$sid])) {
                    $group[$sid] = [
                        'name' => $c->shipper->name,
                        'total' => 0,
                        'statuses' => [],
                    ];
                }
                $group[$sid]['total']++;
                $group[$sid]['statuses'][$c->status] = ($group[$sid]['statuses'][$c->status] ?? 0) + 1;
            }

            // sort shippers by total desc and keep top 8
            uasort($group, function ($a, $b) { return $b['total'] <=> $a['total']; });
            $shipperStats[$key] = array_slice($group, 0, 8, true);
        }
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Total Containers -->
            <a href="{{ route('containers') }}" class="block rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6 hover:shadow">
                <div class="text-sm text-zinc-500">Total Containers</div>
                <div class="mt-3 text-3xl font-semibold">{{ number_format($totalContainers) }}</div>
                <div class="mt-2 text-sm text-zinc-500">View all containers</div>
                <div class="mt-4">
                    <div class="flex items-center gap-2 text-xs text-zinc-500"> 
                        <span>By status:</span>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-200 dark:bg-yellow-900"></span>In transit ({{ $statusCounts['in_transit'] }})</span>
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-200 dark:bg-green-900"></span>Arrived ({{ $statusCounts['arrived'] }})</span>
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-200 dark:bg-blue-900"></span>Departed ({{ $statusCounts['departed'] }})</span>
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-200 dark:bg-red-900"></span>Delayed ({{ $statusCounts['delayed'] }})</span>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-1 h-3">
                        @foreach($statusKeys as $sk)
                            @php $c = $statusCounts[$sk]; $w = $maxCount ? max(2, intval(($c / $maxCount) * 100)) : 2; @endphp
                            <div style="width: {{ $w }}%" class="h-2 rounded" 
                                @if($sk === 'in_transit') style="background:linear-gradient(90deg,#fef3c7,#f59e0b);" @endif
                                @if($sk === 'arrived') style="background:linear-gradient(90deg,#bbf7d0,#10b981);" @endif
                                @if($sk === 'departed') style="background:linear-gradient(90deg,#bfdbfe,#3b82f6);" @endif
                                @if($sk === 'delayed') style="background:linear-gradient(90deg,#fecaca,#ef4444);" @endif
                            ></div>
                        @endforeach
                    </div>
                </div>
            </a>

            <!-- Total Parties -->
            <a href="{{ route('parties') }}" class="block rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6 hover:shadow">
                <div class="text-sm text-zinc-500">Total Parties</div>
                <div class="mt-3 text-3xl font-semibold">{{ number_format($totalParties) }}</div>
                <div class="mt-2 text-sm text-zinc-500">Manage parties</div>
            </a>

            <!-- Open Containers -->
            <a href="{{ route('containers') }}" class="block rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6 hover:shadow">
                <div class="text-sm text-zinc-500">Open Containers</div>
                <div class="mt-3 text-3xl font-semibold">{{ number_format($openContainers) }}</div>
                <div class="mt-2 text-sm text-zinc-500">Not completed yet</div>
            </a>
        </div>

        <!-- Shipper status totals by timeframe -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach(['7d' => 'Last 7 days', '30d' => 'Last 30 days', 'all' => 'All time'] as $tfKey => $tfLabel)
                <div class="block rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4 hover:shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-zinc-500">Shipper Containers</div>
                            <div class="mt-1 text-lg font-semibold">{{ $tfLabel }}</div>
                        </div>
                        <div class="text-xs text-zinc-500">Top shippers</div>
                    </div>

                    <div class="mt-3 space-y-3">
                        @php $list = $shipperStats[$tfKey] ?? []; @endphp
                        @if(empty($list))
                            <div class="text-zinc-500">No data</div>
                        @else
                            @foreach($list as $sid => $sdata)
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0">
                                        <div class="font-medium text-zinc-900 dark:text-white truncate">{{ $sdata['name'] }}</div>
                                        <div class="text-xs text-zinc-500">{{ $sdata['total'] }} containers</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @foreach($statusKeys as $sk)
                                            @php $cnt = $sdata['statuses'][$sk] ?? 0; @endphp
                                            @if($cnt > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($sk === 'in_transit') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                    @elseif($sk === 'arrived') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                    @elseif($sk === 'departed') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                                    @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                    @endif">
                                                    {{ $cnt }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6">
            <h2 class="text-lg font-semibold">Latest Open Containers</h2>
            <p class="text-sm text-zinc-500 mb-4">Recent containers that are not yet completed</p>
            @if($recentOpen->isEmpty())
                <div class="text-zinc-500">No open containers.</div>
            @else
                <ul class="space-y-3">
                    @foreach($recentOpen as $c)
                        <li class="flex items-center justify-between border rounded-md p-3">
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">{{ $c->container_number }}</div>
                                <div class="text-xs text-zinc-500">{{ $c->booking_number }} · {{ $c->transitor?->name ?? $c->shipping?->name ?? '—' }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('container.show', $c->id) }}" class="text-sm text-blue-600 dark:text-blue-400">View</a>
                                <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">{{ ucfirst(str_replace('_',' ', $c->status)) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>
