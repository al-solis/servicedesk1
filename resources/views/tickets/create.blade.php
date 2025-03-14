<section>
<div class="container">
    <h2 class="text-lg font-medium text-gray-900">
            Create New Ticket
    </h2>
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        {{-- @method('PUT') --}}
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <textarea class="form-control" name="subject" placeholder="Enter subject" rows="1" required></textarea>                        
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" placeholder="Enter description" rows="3" required></textarea>                        
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Support Type</label>
                <select name="support_type_id" class="form-control" required>
                    <option value="">Select support type</option>
                        @foreach($supportTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->description }}</option>
                        @endforeach
                </select>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-control" name="status" disabled>
                    <option value="Open">Open</option>
                    <option value="In Progress">In Progress</option>
                    <option value="On-hold">On-hold</option>
                    <option value="Closed">Closed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    
        <div class="modal-footer">
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Add Ticket</button>
        </div>
    </form>
</div>
</section>

