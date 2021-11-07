@extends('layouts.default')


@push('styles')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css"> --}}
<link href="{{ asset('css/croppie.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<!-- Page header -->
<div class="page-header">
    <!-- Toolbar -->
    <div class="navbar navbar-default navbar-xs">
        <ul class="nav navbar-nav visible-xs-block">
            <li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
        </ul>

        <div class="navbar-collapse collapse" id="navbar-filter">
            <ul class="nav navbar-nav element-active-slate-400">
                <li class="active"><a href="#edit-Profile" data-toggle="tab"><i class="icon-calendar3 position-left"></i> Update Profile Image</a></li>
            </ul>
        </div>
    </div>
    <!-- /toolbar -->
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- User profile -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-body border-top-info text-center">
                @if (session('msgType'))
                    <div id="msgDiv" class="alert alert-{{session('msgType')}} alert-styled-left alert-arrow-left alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-styled-left alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">Opps!</span> {{ $error }}.
                    </div>
                    @endforeach
                @endif
                
                <form class="form-horizontal" action="{{route('changeProfileUpdate')}}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="row">
                        <div class="row text-center">
                            <div class="col-lg-10">
                                <div id="upload-demo"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="file" id="image" name="image" class="btn btn-primary btn-block" value="{{asset('uploads/studentProfile/'.$student_info->image)}}" required>
                            </div>
                            <div class="col-lg-4 pull-right">
                                <p><button id="submit" type="button" class="btn btn-info btn-block btn-block upload-image" style="margin-top:2%;">Set Data</button></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /user profile -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script> --}}
<script type="text/javascript" src="{{ asset('js/croppie.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 6000);
        @endif
        
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
     
     
    var resize = $('#upload-demo').croppie({
        enableExif: true,
        enableOrientation: true,    
        viewport: { // Default { width: 100, height: 100, type: 'square' } 
            width: 250,
            height: 250,
            type: 'square' //square
        },
        boundary: {
            width: 350,
            height: 350
        }
    });
    
    resize.croppie('bind', {
        url: "{{url('uploads/studentProfile/'.$student_info->image)}}"
    })
     
    $('#image').on('change', function () { 
        $(".upload-image").show();
        var reader = new FileReader();
        reader.onload = function (e) {
            resize.croppie('bind',{
                url: e.target.result
            }).then(function(){
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
    });
     
     
    $('.upload-image').on('click', function (ev) {
        resize.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (img) {
            $.ajax({
                url: "{{route('changeProfileUpdate')}}",
                type: "POST",
                data: {"image":img},
                success: function (data) {
                    console.log('uploaded');
                    location.replace("{{route('profile')}}");
                }
            });
        });
    });
</script>
@endpush
