{{-- <div class="modal-header">
    <h5 class="modal-title">Edit Ticket</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div> --}}
<div class="container">
    <h2 class="text-lg font-medium text-gray-900">
            Edit Ticket
    </h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="description" class="form-label">Subject</label>
            <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $ticket->description) }}" 
            @if ($ticket->user != Auth::user())
                readonly 
            @endif 
            required></input>
        </div>

        <!-- Support Type -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="type" class="form-label">Support Type</label>
                <select class="form-control" id="type" name="type" required>
                    @foreach($supportTypes as $type)
                        <option value="{{ $type->id }}" {{ $ticket->type == $type->id ? 'selected' : '' }}>
                            {{ $type->description }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">                
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required 
                    @if(Auth::user()->usertype == 'User') disabled @endif>                    
                    <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="On-hold" {{ $ticket->status == 'On-hold' ? 'selected' : '' }}>On-hold</option>
                    <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                    <option value="Cancelled" {{ $ticket->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>        

        <!-- Messages -->
        {{-- <h5>Ticket Messages</h5> --}}
        <ul class="list-group mb-3">
            @foreach($ticket->details as $detail)
                <li class="list-group-item">
                    <strong>
                        {{ optional($detail->user)->lname ?? 'System' }},
                        {{ optional($detail->user)->fname ?? '' }},
                        {{ optional($detail->user)->mname ? substr(optional($detail->user)->mname, 0, 1) . '.' : '' }}
                        :</strong> 
                    {{ $detail->message }}
                    <br>
                    <small class="text-muted">{{ $detail->date_created }}</small>
                </li>
            @endforeach
        </ul>

        <!-- Allow new message only if logged-in name ≠ edited name -->
        @if($ticket->user_id != Auth::user()->id or Auth::user()->usertype != 'User')
            <div class="mb-3">
                {{-- <label for="message" class="form-label">Message</label> --}}
                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter message"></textarea>
            </div>
            {{-- @elseif ($ticket->user_id == Auth::user()->id)
            <div class="mb-3">             
                @php
                    // Fetch the latest message of the current user for this ticket
                $userMessage = $ticket->details->where('user_id', Auth::user()->id)->last();
                @endphp                   
                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter message">{{ old('message', $userMessage ? $userMessage->message : '') }}</textarea>
            </div> --}}
        @endif

        <div class="modal-footer">            
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Ticket</button>        
        </div>
        
    </form>
</div>
