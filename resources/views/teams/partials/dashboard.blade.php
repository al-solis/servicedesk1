<title>Team Ticket Queues</title>
<h2 class="text-2xl font-bold">{{ optional($teams->first())->name }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 h-fill">


    @php
        $statuses = [
            'Open' => ['bg' => 'bg-blue-400', 'text' => 'text-blue-900'],
            'In Progress' => ['bg' => 'bg-yellow-400', 'text' => 'text-yellow-900'],
            'On-hold' => ['bg' => 'bg-orange-400', 'text' => 'text-orange-900'],
            'Closed' => ['bg' => 'bg-green-400', 'text' => 'text-green-900'],
        ];
    @endphp

    @foreach ($statuses as $status => $style)
        <div class="bg-white border border-gray-200 rounded-lg shadow h-fill flex flex-col">
            <div class="{{ $style['bg'] }} text-white p-4 rounded-t-lg flex justify-between">
                <h5 class="font-bold">{{ $status }}</h5>
                <span class="bg-gray-100 text-xs {{ $style['text'] }} font-semibold px-2.5 py-0.5 rounded">
                    {{ $tickets->where('status', $status)->count() }}
                </span>
            </div>
            <div class="p-4 overflow-y-auto flex-grow">
                @foreach ($status == 'Others' ? $tickets->whereNotIn('status', ['Open', 'In Progress', 'On-hold', 'Closed', 'Cancelled']) : $tickets->where('status', $status) as $ticket)
                    <div class="mb-2 border-b pb-2">
                        <a href="{{ route('tickets.show', $ticket->id) }}"
                            class="font-semibold text-blue-600 hover:underline">
                            {{ $ticket->description }}
                        </a>
                        <p class="text-sm text-gray-500">{{ Str::limit($ticket->message, 80) }}</p>

                        <!-- Single Row Flex Layout -->
                        <div class="text-xs text-gray-400 flex justify-between items-center">
                            <span>#{{ $ticket->id }}</span>
                            <span>&nbsp;{{ $ticket->lname }}, {{ $ticket->fname }}</span>
                            <span
                                class="ml-auto">{{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
