{{--Data Table CSS--}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
@extends('layouts.app')

@section('navbar-content')
<section>
    <body>
        <div class="container py-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title font-bold mb-4">Ticket Summary Report by Support Member</h5>
                    <input type="date" id="start_date" class="text-sm font-medium text-gray-700">
                    <input type="date" id="end_date" class="text-sm font-medium text-gray-700">
                    <button id="filter" class="ml-2 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Filter</button>
                    <button id="reset" class="ml-2 mb-2 text-white inline-flex items-center bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Reset</button>
                </div>

                <div class="card-body block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    <div class="table-responsive block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    @if (Auth::user()->usertype != 'User')
                                        <th>Name</th>
                                    @else
                                        <th>Date</th>
                                    @endif 
                                    <th>Open</th>
                                    <th>In Progress</th>
                                    <th>On-hold</th>
                                    <th>Closed</th>
                                    <th>Cancelled</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
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
            $(document).ready(function() {
                var userType = "{{ Auth::user()->usertype }}"; 
            
                var table = $('.datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export to Excel',
                            title: function () {
                                let startDate = $('#start_date').val() || '';
                                let endDate = $('#end_date').val() || '';
                                let currentDate = new Date().toLocaleDateString('en-US', { 
                                    month: '2-digit', day: '2-digit', year: 'numeric'
                                }); // Ensures MM/DD/YYYY format
            
                                return startDate ? `Ticket Summary Report as of ${startDate} - ${endDate}` 
                                                 : `Ticket Summary Report as of ${currentDate}`;
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'Export to PDF',
                            title: function () {
                                let startDate = $('#start_date').val() || '';
                                let endDate = $('#end_date').val() || '';
                                let currentDate = new Date().toLocaleDateString('en-US', { 
                                    month: '2-digit', day: '2-digit', year: 'numeric'
                                });
            
                                return startDate ? `Ticket Summary Report as of ${startDate} - ${endDate}` 
                                                 : `Ticket Summary Report as of ${currentDate}`;
                            },
                            orientation: 'portrait', 
                            pageSize: 'A4'
                        }
                    ],
                    ajax: {
                        url: '{{ route("reports.index-summary") }}',
                        type: 'GET',
                        data: function (d) {
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                        }
                    },
                    columns: [
                        ...(userType !== 'User' ? [{ data: 'support_member', name: 'support_member' }] 
                                    : [{
                                        data: 'formatted_date_created',
                                        name: 'formatted_date_created',
                                        render: function(data, type, row) {
                                            if (!data) return ''; 
                                            let date = new Date(data);
                                            return date.toLocaleDateString('en-US', { 
                                                month: '2-digit', day: '2-digit', year: 'numeric' 
                                            }); 
                                        }
                                    }]
                        ),
                        { data: 'open_count', name: 'open_count' },
                        { data: 'in_progress_count', name: 'in_progress_count' },
                        { data: 'on_hold_count', name: 'on_hold_count' },
                        { data: 'closed_count', name: 'closed_count' },
                        { data: 'cancelled_count', name: 'cancelled_count' },
                        { data: 'total_count', name: 'total_count' }
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
