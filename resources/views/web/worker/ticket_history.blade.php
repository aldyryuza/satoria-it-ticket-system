@canAccess('worker/ticket-history','view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ticket History: {{ $ticket->ticket_number }}</h5>
                <p class="card-text">
                    History of changes for this ticket.
                </p>

                <div class="row mt-3">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-upper">user</th>
                                    <th class="text-upper">action</th>
                                    <th class="text-upper">description</th>
                                    <th class="text-upper">created_at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $history)
                                <tr>
                                    <td>{{ $history->user->name ?? 'System' }}</td>
                                    <td>{{ $history->action }}</td>
                                    <td>{{ $history->description }}</td>
                                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('worker.my_tickets') }}" class="btn btn-secondary">Back to My Tickets</a>
                </div>

            </div>
        </div>
    </div>
</div>
@else
@include('errors.no_akes')
@endcanAccess
