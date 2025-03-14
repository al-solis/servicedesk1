<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    
    {{--Data Table CSS--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
  

    {{-- <link rel="shortcut icon" href="favicon.png" type="image/png"> --}}
    <title>User</title>
</head>
<body>
    <div class="container py-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">DataTables</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </thead>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td>{{$ticket->id}}</td>
                                    <td>{{$ticket->description}}</td>
                                    <td>{{$ticket->priority}}</td>
                                    <td>{{$ticket->status}}</td>
                                </tr>
                                
                            @empty
                                <td class="col-span-4">No data found</td>
                            @endforelse
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--JQuery CDN--}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{--Data Table JS--}}
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

    <script type="text/javascript">
        //Initializing Datatable 
        $(document).ready(function(){
            $('.datatable').DataTable();
        });
    </script>
</body>
</html>
