<select required class="select-search" name="batch_id" id="batch_id">
    <option value="">Select Batch</option>
    @foreach($running_batches as $batch)
    <option value="{{$batch->id}}">{{$batch->batch_no}} (Total {{$batch->avail_assignment_qty}})</option>
    @endforeach
</select>
