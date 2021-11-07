@extends('support.layouts.default')

@push('styles')
    <style>
        .theme_perspective {
            width: 120px!important;
        }
        .theme_perspective .pace_activity {
            background-color: rgb(116, 113, 113)!important;
        }
        
    </style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>Home</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">
    <div class="row mb-10">
        <div class="col-md-12">
            <!-- Second navbar -->
            <div class="navbar navbar-default navbar-xs">
                <ul class="nav navbar-nav no-border visible-xs-block">
                    <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-circle-down2"></i></a></li>
                </ul>

                <div class="navbar-collapse collapse" id="navbar-second-toggle">
                    <ul class="nav navbar-nav" id="review_sub_menu">
                        <input type="hidden" id="taken_assignment_id" value="{{$assignment_subm_taken_id}}">
                        <li><a href="checkReviewInstruction">Instruction</a></li>
                        <li class="active"><a href="checkAssignmentDetails">Assignment</a></li>
                        <li><a href="checkAssignmentDiscussion">Comments</a></li>
                    </ul>
                    <a href="{{route('support.checkAssignmentList')}}" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs ml-15"><i class="icon-undo2"></i> Back To List</a>
                </div>
            </div>
            <!-- /second navbar -->
        </div>
    </div>
    
    <div class="" id="load_content" style="min-height: 90vh;">
    </div>

</div>
<!-- /content area -->
@endsection

@push('javascript')

<script type="text/javascript">
    $(document).ready(function() {
        var ajax_url = location.hash.replace(/^#/, '');
        if (ajax_url.length < 1) {
            ajax_url = 'checkAssignmentDetails';
            window.location.hash = ajax_url;
        }
        // For Page Refresh / First time loaded
        var taken_assignment_id = $('#taken_assignment_id').val();
        LoadPageContent(ajax_url, taken_assignment_id);

        $('#review_sub_menu li a').removeClass('active');
        $('#review_sub_menu li').removeClass('active');
        $('#review_sub_menu li').find('a[href='+ajax_url+']').parent('li').addClass('active');
        $('#review_sub_menu li').find('a[href='+ajax_url+']').addClass('active');

        // sub menu
        $('#review_sub_menu li').on('click', 'a', function(e) {
            e.preventDefault();
            $('#load_content').show();
            $('#review_sub_menu li a.active').removeClass('active');
            $('#review_sub_menu li').removeClass('active');
            $(this).parent('li').addClass('active');
            $(this).addClass('active');

            var url = $(this).attr('href');
            if (url) {
                if (url != '#') {
                    window.location.hash = url;
                    LoadPageContent(url, taken_assignment_id);
                }
            }
        })
        // end sub menu
    });

    function LoadPageContent(url, taken_assignment_id) {
        $('#load_content').html(`<div class="theme_perspective preloader"><div class="pace_activity"></div><div class="pace_activity"></div><div class="pace_activity"></div><div class="pace_activity"></div></div>`);
        $.ajax({
            mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
            url: url,
            data: {'taken_assignment_id': taken_assignment_id},
            type: "GET",
            dataType: "html",
            success: function (data) {
                if (parseInt(data) === 0) {
                    $('.preloader').show();
                } 
                else {
                    $('.preloader').hide();
                    $('#load_content').html(data);
                }
            }
        });
    }
    

</script>    
@endpush
