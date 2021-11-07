<form class="form-horizontal form-validate-jquery" action="{{route('provider.updatePaymentAction', ['assign_batch_std_id' => $assign_batch_std_id] )}}" method="POST">
    @csrf
    <div class="panel panel-flat">
        <div class="panel-body" id="modal-container">

            <div class="form-group">
                <label class="control-label col-lg-3">Select Payment</label>
                <div class="col-lg-9">
                    <select class="select2 select-search col-lg-8" id="select_teacher" name="payment_system_id" required="">
                        <option value="">Select Payment System</option>
                        @foreach($payment_systems as $system)
                            <option value="{{$system->id}}" @if (@$assign_batch_std_info->payment_system_id == $system->id) selected @endif>
                               {{$system->payment_type}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <span style="color: red;">All Payment will be Generate for this student & First Payment will be done!</span>

        </div>
    </div>
    </form>
    <script type="text/javascript">
        $("#select_teacher").select2({ dropdownParent: "#modal-container" });
    </script>
    