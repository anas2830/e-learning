@extends('layouts.default')

@push('styles')
<link href="{{ asset('web/slick/customSlider.css') }}" rel="stylesheet" type="text/css"/>

<style>

</style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li class="active"><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- COURSE LIST VIEW AS GRID -->
    <div class="slider-grid-section">
        <input type="hidden" id="total-item" />
        <input type="hidden" id="group-no" />
        <div class="slider-grid-container" id="class-content-grid" data-masonry='{"percentPosition": true }'>
            
        </div>
        <div id="loading" class="loading" style="display:none;">
            
        </div>
    </div>
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript" src="{{ asset('web/js/masonry.pkgd.min.js') }}"></script>
<script type="text/javascript">
    // Slider
    $(document).ready(function(){
        items_per_group = 6; //Set Item per group
        searchLoad(items_per_group, 0);

        //DETECT PAGE SCROLLING
        $(window).scroll(function() { //detect page scroll
            if ((($(window).scrollTop() + $(window).height()) >= ($(document).height() - 300)) && $('#loading').is(':hidden')) {  //user scrolled to bottom of the page?

                $('#class-content-grid').addClass('course-overlay');
                $('#loading').show();

                var track_load = parseInt($("#group-no").val()) + 1; //total loaded record group(s)
                var totalItem = parseInt($("#total-item").val());
                var total_groups = Math.ceil(totalItem / items_per_group); //total record group(s)
                if (track_load < total_groups) { //there's more data to load
                    searchLoad(items_per_group, track_load);
                } else {
                    $('#class-content-grid').removeClass('course-overlay');
                    $('#loading').hide();
                }
            }
        });
    });

    function searchLoad(items_per_group, group_number, html_blank=false) {
        $.ajax({
            url: "{{route('stdSuccessListAjax')}}",
            type: "GET",
            dataType: "html",
            data: {'items_per_group':items_per_group, 'group_number':group_number},
            success: function(data) {
                // alert(data);
                var x = data.split("/~.~/");
                var searchCount = parseInt(x[0]);
                $("#total-item").val(searchCount);
                $("#group-no").val(group_number);
                if(html_blank) {
                    $('#class-content-grid').html(x[1]).show(300);
                } else {
                    $('#class-content-grid').append(x[1]).show(300);
                }
                $('#class-content-grid').removeClass('course-overlay');
                $('#loading').hide();
            }
        });
    }
</script>
@endpush

