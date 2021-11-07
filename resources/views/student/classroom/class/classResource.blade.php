<!-- Simple panel -->
<div class="panel panel-white">
    @if (!empty($class_resource->class_resource))
    <input type="hidden" id="assign_batch_class_id" value="{{$assign_batch_class_id}}" />
    <div class="panel-heading">
        <h6 class="panel-title">{{@$class_resource->class_name}} Overview</h6>
    </div>

    <div class="panel-body">
        <p class="content-group">
            {!! @$class_resource->class_resource !!}
        </p>
    </div>
    @else
    <input type="hidden" id="assign_batch_class_id" value="{{$assign_batch_class_id}}" />
        <div class="panel-body">
            <p class="content-group">
                There has no Resource!!!
            </p>
        </div>
    @endif
</div>
<!-- /simple panel -->