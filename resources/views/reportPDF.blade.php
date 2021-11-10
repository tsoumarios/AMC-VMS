<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="icon" type="image/png" href="{{asset('logo.png')}}"/>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @livewireStyles
        <style>
            body { font-family: DejaVu Sans, sans-serif; }
            body { font-family: DejaVu Sans, sans-serif; }
            
            table {
                overflow: scroll;
            }
            
            td {
                text-align: center;
                padding: 10px 25px;
                min-width: 150px;
                min-width: 250px;
            }
            tr {
                border-bottom: 1px solid rgba(128, 128, 128, 0.253);
            }
            
            .TFtable {
                width: 100%;
                border-collapse: collapse;
                box-shadow: 3px 5px 16px #d3dfef;
            }
            .TFtable td {
                padding: 7px;
                border: white 1px solid;
                font-size: 9px;
            }
            /* provide some minimal visual accomodation for IE8 and below */
            .TFtable tr {
                background: #dae5f4;
            }
            /*  Define the background color for all the ODD background rows  */
            .TFtable tr:nth-child(odd) {
                background: #dae5f4;
            }
            /*  Define the background color for all the EVEN background rows  */
            .TFtable tr:nth-child(even) {
                background: #6abcbe45;
            }
        </style>
        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="pdf">
<h4 style="color:gray">Αναφορά επισκεπτών για την {{$date_search}}</h4>
@if($visits->count() > 0)
            <table class="table-auto TFtable">
                <thead>
                    <tr>
                        <th style="font-size:10px;">Όνομα</th>
                        <th style="font-size:10px;">Εταιρεία</th>
                        <th style="font-size:10px;">Εσωτερικός χώρος επίσκεψης</th>
                        <th style="font-size:10px;">Εσωτερικός αποδέκτης</th>
                        <th style="font-size:10px;">Τηλέφωνο</th>
                        <th style="font-size:10px;">E-mail</th>
                        <th style="font-size:10px;">Ημερομηνία και διάρκεια</th>
                        <th style="font-size:10px;">Κατάσταση έγκρισης από HR</th>
                        <th style="font-size:10px;">Αναγκαιότητα Επίσκεψης</th>
                        <th style="font-size:10px;">Λόγος επίσκεψης</th>
                        <th style="font-size:10px;">Κατάσταση Επίσκεψης</th>
                    </tr>
                </thead>
                <tbody>

                 
                @foreach($visits as $visit)
                        <tr>
                            <td>{{ $visit->visitor->fullname }}</td>
                            <td>{{ $visit->visitor->company }}</td>
                            @if($visit->department)
                            <td>{{ $visit->department->name }}</td>
                            @else
                            <td>-</td>
                            @endif

                            @if($recipients->find($visit->recipient_id))
                            <td>{{ $recipients->find($visit->recipient_id)->name }}</td>
                            @else
                            <td>-</td>
                            @endif

                            <td>{{ $visit->visitor->phone }}</td>
                            <td>{{ $visit->visitor->email }}</td>
                            <td>{{ $visit->date }}</br><small>{{ $visit->checkin }}-{{ $visit->checkout }}</small></td>

                            @if( auth()->user()->permissions->whereIn('pivot.permission_id', 6)->count() > 0 )
                                @if($visit->hr_approval)
                                    <td>Εγκεκριμένο από HR</td>
                                @else
                                    <td>Αναμένεται έγκριση από HR</td> 
                                @endif

                            @elseif( auth()->user()->permissions->whereIn('pivot.permission_id', 5)->count() > 0 )

                                @if($visit->hr_approval)
                                <td>Εγκεκριμένο από HR <i class="fas pl-2 fa-check"></i></td>
                                @else
                                    <td  wire:click="hr_approval({{ $visit->id }})"><small class="form-button">Εγκριση HR</small></td> 
                                @endif

                            @else
                            
                            @if($visit->hr_approval)
                                <td>Εγκεκριμένο από HR <i class="fas pl-2 fa-check"></i></td>
                                @else
                                <td>Αναμένεται έγκριση από HR</td> 
                                @endif
                            @endif
                                    
                            @if($visit->necessity)
                                <td>{{ $visit->necessity }}</td>
                            @else
                                <td>-</td>
                            @endif
                                
                            @if($visit->reason)
                                <td>{{ $visit->reason }}</td>
                            @else
                                <td>-</td>
                            @endif

                            @if($visit->completed)
                                <td>Ολοκληρωμένη <i class="fas pl-2 fa-check"></i></td>
                                @else
                                <td  wire:click="completed({{ $visit->id }})"><small class="form-button">Ολοκλήρωση</small></td>
                            @endif

                        </tr>
                    @endforeach
                    
            </tbody>
            </table>
        @endif
</body>
</html>