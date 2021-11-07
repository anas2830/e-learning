<div class="panel panel-flat">
    <div class="panel-body">
        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
                <tr>
                    <th>Title</th>
		  	    	<th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $requestDetails->request_title }}</td>
                    <td>{!! $requestDetails->request_details !!}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
