<form class="form-horizontal form-validate-jquery" action="{{route('provider.updateSchedule', [$batch_id])}}" method="POST">
   @csrf
	<div class="panel panel-flat">
	    <div class="panel-body">
			@foreach($total_days as $key => $day)
			    <div class="form-group row">
			    		<label class="control-label checkbox-inline col-md-6"> {{$day->day_name}} 
			    			<input type="checkbox" name="days[]" value="{{$day->dt}}" class="pt5" @if(isset($day->schedule)) checked @endif>
			    		</label>
			    		<div class="col-md-6">
				        	<input type="time" name="start_times[]" value="{{@$day->schedule->start_time}}">
				        </div>
			    </div>
			@endforeach
		</div>
	</div>
</form>
