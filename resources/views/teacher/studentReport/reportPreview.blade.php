<html moznomarginboxes mozdisallowselectionprint>
  <head>
    <!--<meta charset="utf-8">-->
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Student Details Report</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/> --}}
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/css/minified/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        @media print {   

            .print_button {
                display: none ;
            }
            table td {
                font-size: 14px;
            }

            table th{
                font-size: 14px;
                font-weight: normal !important;
            }
            table tbody tr td {
                font-family: 'Open Sans', sans-serif;
                font-size: 12px;
                padding: 2px ;
            }

        }
    </style>
  </head>
    
<body>
    <div class="container">
        <div class="print_button" style="float: right;">
            <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        </div>

        <div class="panel panel-flat">
            <div class="panel-body no-padding-bottom">
                <div class="row">
                    <div class="col-md-6 content-group">
                        <img src="{{ asset('web/img/fav.png') }}" class="content-group mt-10 mb-10" alt="" style="width: 70px; height: 70px;">
                        <ul class="list-condensed list-unstyled">
                            <li>LFWF Academy</li>
                            <li>Suihari Dinajpur</li>
                            <li><a href="tel:+8801889972995"> +8801889972995</a></li>
                        </ul>
                    </div>
    
                    <div class="col-md-6 content-group">
                        <div class="invoice-details">
                            <h5 class="text-uppercase text-semibold">Md. Rafikul Islam Rafi</h5>
                            <ul class="list-condensed list-unstyled">
                                <li>Course Name: <span class="text-semibold">January 12, 2015</span></li>
                                <li>From Date: <span class="text-semibold">{{ date("jS F, Y", strtotime($from_date)) }}</span></li>
                                <li>To Date: <span class="text-semibold">{{ date("jS F, Y", strtotime($to_date)) }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="col-sm-8">Topic</th>
                            <th class="col-sm-2">Base/Status</th>
                            <th class="col-sm-2">Gained</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($assign_batch_classess) > 0)
                            @foreach ($assign_batch_classess as $batch_class)
                                <tr class="active border-double">
                                    <td colspan="2">{{ $batch_class->class_name }}</td>
                                    <td class="text-right">
                                        <span class="progress-meter" id="today-progress" data-progress="30"></span>
                                    </td>
                                </tr>
    
                                <tr>
                                    <td colspan="2">
                                        Attendance</
                                        {{-- <span class="text-muted">One morning, when Gregor Samsa woke from troubled.</span> --}}
                                    </td>
                                    <td><span class="text-semibold">{{ $batch_class->attend_status }}</span></td>
                                </tr>
    
                                <tr>
                                    <td colspan="2">
                                        Class Performance Mark</
                                    </td>
                                    <td><span class="text-semibold">{{ $batch_class->class_mark }}</span></td>
                                </tr>
    
                                <tr>
                                    <td colspan="2">
                                        Class Quiz/Exam</
                                    </td>
                                    <td><span class="text-semibold">{{ $batch_class->quiz_mark }}</span></td>
                                </tr>
    
                                <tr>
                                    <td>
                                        Class Practice Time</
                                    </td>
                                    <td>
                                        <div class="text-default text-semibold"> {{ Helper::secondsToTime($batch_class->base_practice_time) }} </div>
                                        <div class="text-muted text-size-small">
                                            <span class="status-mark border-blue position-left"></span>
                                            Base
                                        </div>
                                    </td>
                                    <td><span class="text-semibold">{{ Helper::secondsToTime($batch_class->final_practice_time) }}</span></td>
                                </tr>
    
                                @if (count($batch_class->assignments) > 0)
                                    @foreach ($batch_class->assignments as $assignment_key => $class_assignment)
                                        <tr>
                                            <td>
                                                Assignment: {{++$assignment_key}} {{ $class_assignment->title }}
                                            </td>
                                            <td>{{ $class_assignment->assignment_submit_status }}</td>
                                            <td><span class="text-semibold">{{ $class_assignment->assignment_mark }}</span></td>
                                        </tr>
                                    @endforeach
                                @endif
    
                                @if (count($batch_class->videos) > 0)
                                    @foreach ($batch_class->videos as $video_key => $class_video)
                                        <tr>
                                            <td>
                                                Video: {{++$video_key}} {{ $class_video->video_title }}
                                            </td>
                                            <td>
                                                <div class="text-default text-semibold"> {{ Helper::secondsToTime($class_video->video_duration) }} </div>
                                                <div class="text-muted text-size-small">
                                                    <span class="status-mark border-blue position-left"></span>
                                                    Base
                                                </div>
                                            </td>
                                            <td><span class="text-semibold">{{ $class_video->watched_duration }}</span></td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">
                                    <h6 class="no-margin">No Data Found!!!</h6>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
    
        </div>
        
    </div>
</body>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/bootstrap.min.js') }}"></script>

<script type="text/javascript">
    function printDocument() {
        window.print();
    }

</script>
</html>