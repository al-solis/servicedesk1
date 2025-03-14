{{-- <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    
    {{--Data Table CSS--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    {{-- <link rel="shortcut icon" href="favicon.png" type="image/png"> --}}
    {{-- <title>User</title> --}}
{{-- </head> --}}
@extends('layouts.app')
@section('navbar-content')    
<section>
    <body>
        <div class="container py-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title font-bold mb-4">Ticket Details Report</h5>
                    <input type="date" id="start_date" class="text-sm font-medium text-gray-700">
                    <input type="date" id="end_date" class="text-sm font-medium text-gray-700">
                    <button id="filter" class="ml-2 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Filter</button>
                    <button id="reset"class="ml-2 mb-2 text-white inline-flex items-center bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Reset</button>
                </div>

                <div class="card-body block mb-2 text-sm font-medium text-gray-900 dark:text-white">                    
                    <div class="table-responsive block mb-2 text-sm font-medium text-gray-900 dark:text-white">                        
                        <table class="table table-striped datatable ">
                            <thead>   
                                <th>ID</th>
                                <th>Requester</th>                         
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Last Update</th>
                            </thead>                            
                            {{-- <tbody>
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
                            </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{--JQuery CDN--}}
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        
        {{--Data Table JS--}}
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

        <!-- DataTables Buttons JS -->
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

        <!-- Required for Excel Export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        <!-- Required for PDF Export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

        <script type="text/javascript">
            //Initializing Datatable 
            $(document).ready(function(){
                var table = $('.datatable').DataTable({
                    serverSide: true,
                    processing: true,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export to Excel',
                            title: function () {
                                let startDate = $('#start_date').val() || 'Start Date';
                                let endDate = $('#end_date').val() || 'End Date';
                                let currentDate = new Date().toLocaleDateString('en-US')
                                if (startDate === 'Start Date')
                                {
                                    return 'Ticket Report as of ' + currentDate
                                }else{
                                    return 'Ticket Report as of ' + startDate + ' - ' + endDate;
                                }                                
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'Export to PDF',
                            title: function () {
                            let startDate = $('#start_date').val() || 'Start Date';
                            let endDate = $('#end_date').val() || 'End Date';
                            let currentDate = new Date().toLocaleDateString('en-US')
                                if (startDate === 'Start Date')
                                {
                                    return 'Ticket Report as of ' + currentDate
                                }else{
                                    return 'Ticket Report as of ' + startDate + ' - ' + endDate;
                                }
                            },
                            orientation: 'portrait', // Use 'portrait' or 'landscape'
                            pageSize: 'A4'
                        }
                    ],
                    ajax: {
                        url: '{{ route("reports.index-detail") }}',
                        data: function (d) {
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                        }
                    },
                    columns: [                    
                        { data: 'id', name: 'id', searchable: false},
                        { data: 'uname', name: 'uname'},
                        { data: 'description', name: 'description'},
                        { data: 'priority', name: 'priority'},
                        { data: 'status', name: 'status'},
                        { data: 'date_created', name: 'date_created'},
                        { data: 'updated_at', name: 'updated_at'},
                    ]
                });

                // Filter button click
                $('#filter').click(function () {
                    table.ajax.reload();
                });

                // Reset button click
                $('#reset').click(function () {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    table.ajax.reload();
                });

            });
        </script>
    </body>
</section>
@endsection