<form class="form-horizontal form-validate-jquery" action="{{route('teacher.updateClassDriveLink', [$assign_batch_class_id, $batch_id])}}" method="POST">
@csrf
<div class="panel panel-flat">
    <div class="panel-body" id="modal-container">
        <div class="hints">
            <h4>Example: <del style="color: red;">https://youtu.be/</del><span style="color: green">BSM-rJzVank</span></h4>
        </div>
        @if (count($drive_links) > 0)
            @foreach ($drive_links as $link)
            <div class="form-group">
                <label class="control-label col-lg-12">Video ID 1</label>
                <div class="col-lg-12">
                    <input type="text" name="drive_link[]" class="form-control" placeholder="BSM-rJzVank" value="{{$link->video_id}}">
                </div>
            </div>
            @endforeach
        @else
            <div class="form-group">
                <label class="control-label col-lg-12">Video ID 1</label>
                <div class="col-lg-12">
                    <input type="text" name="drive_link[]" class="form-control" placeholder="BSM-rJzVank">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-12">Video ID 2</label>
                <div class="col-lg-12">
                    <input type="text" name="drive_link[]" class="form-control" placeholder="BSM-rJzVank">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-12">Video ID 3</label>
                <div class="col-lg-12">
                    <input type="text" name="drive_link[]" class="form-control" placeholder="BSM-rJzVank">
                </div>
            </div>
        @endif
    </div>
</div>
</form>
