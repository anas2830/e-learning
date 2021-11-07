<select required class="select-search" name="batch_id" id="batch_id">
    <option value=""></option>
    @foreach($batches as $batch)
    <option value="{{$batch->id}}">{{$batch->batch_no}}</option>
    @endforeach
</select>
