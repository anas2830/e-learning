@if(!empty($running_class_info->class_resource))
    <div class="panel panel-white assignment-post-box">
        <div class="panel-heading">
            <h5 class="panel-title">{{$running_class_info->class_name}}</h5>
        </div>

        <div class="panel-body">
            <p class="content-group">{!! $running_class_info->class_resource !!}</p>
        </div>
    </div>
@else
    <div class="panel panel-white">
        <div class="panel-body">
            <h6 class="panel-title text-center">Resource Not Found !!!</h6>
        </div>
    </div>
@endif