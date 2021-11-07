<form class="form-horizontal form-validate-jquery" action="{{route('provider.stdPaymentManualAction', ['pay_history_id'=>$pay_history_id])}}" method="POST">
@csrf
    <div class="panel panel-flat">
        <table class="table table-hover">
            <tbody>
              <tr>
                <td>SL.</td>
                <td>:</td>
                <td>{{$payment_history_info->serial_no}}</td>
              </tr>
              <tr>
                <td>Amount</td>
                <td>:</td>
                <td>{{$payment_history_info->amount}}</td>
              </tr>
              <tr>
                <td>Start Date</td>
                <td>:</td>
                <td>{{$payment_history_info->start_date}}</td>
              </tr>
              <tr>
                <td>End Date</td>
                <td>:</td>
                <td>{{$payment_history_info->end_date}}</td>
              </tr>
            </tbody>
        </table>
        <div class="panel-body" id="modal-container">
            <div class="form-group">
                <select class="select2 select-search col-lg-12" id="is_running" name="is_running" required="">
                    <option value="">Select Status</option>
                    <option value="1">Paid</option>
                </select>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
	$("#is_running").select2({ dropdownParent: "#modal-container" });
</script>

