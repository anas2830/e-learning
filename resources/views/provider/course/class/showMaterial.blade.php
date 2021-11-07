<div class="panel panel-flat">
    <div class="panel-body">
        <table class="table table-bordered table-hover datatable-highlight" id="courseTable">
            <thead>
                <tr>
                    <th>SL</th>
		  	    	<th>Video ID</th>
		  	 		<th>Video Title</th>
		  	 		<th>Video Duration(s)</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($class_materials))
                     @foreach($class_materials as $key => $material)
				  	 	<tr>
				  	 		<td>{{$key+1}}</td>
				  	 		<td>{{ $material->video_id }}</td>
				  	 		<td>{{ $material->video_title }}</td>
				  	 		<td>{{ $material->video_duration }}</td>
				  	 	</tr>
				    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
