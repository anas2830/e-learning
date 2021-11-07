<!-- Modal with invoice -->
<div class="panel-body no-padding-bottom" id="DivIdToPrint" style="background: #fafafa; border-radius: 10px;">
    <div class="row">
        <div class="col-md-6 content-group">
            <img src="{{ asset('web/img/fav.png') }}" class="content-group mt-10 mb-10" alt="" style="width: 70px; height: 70px;">
            <ul class="list-condensed list-unstyled">
                <li>LFWF Academy</li>
                <li>Suihari Dinajpur</li>
                <li><a href="tel:+8801889972995"> +8801889972995</a></li>
            </ul>
        </div>

        <div class="col-md-6 content-group">
            <div class="invoice-details">
                <h5 class="text-uppercase text-semibold">Invoice #0{{$payment_history_details->serial_no}}</h5>
                <ul class="list-condensed list-unstyled">
                    <li>Date: <span class="text-semibold">{{ date("jS F, Y", strtotime($payment_history_details->start_date)) }}</span></li>
                    <li>Due date: <span class="text-semibold">{{ date("jS F, Y", strtotime($payment_history_details->end_date)) }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row invoice-payment">
        <div class="col-sm-7">
            <div class="content-group">
                <h6>Invoice To:</h6>
                <div class="mb-15 mt-15">
                    <img src="assets/images/signature.png" class="display-block" style="width: 150px;" alt="">
                </div>

                <ul class="list-condensed list-unstyled text-muted">
                    <li><h6>{{$user_info->name}} [{{$user_info->student_id}}]</h6></li>
                    <li>Batch: {{$batch_no}}</li>
                    <li>Course: {{$course_info->course_name}}</li>
                    <li>{{$user_info->address}}</li>
                    <li>{{$user_info->phone}}</li>
                    <li>{{$user_info->email}}</li>
                </ul>
            </div>
        </div>

        <div class="col-sm-5">
            <div class="content-group">
                <h6>
                    @if ($payment_history_details->is_running == 1) 
                    Total Paid by @if ($payment_history_details->paid_from == 2) {{'Menual'}} @else {{@$payment_success->payment_method}} @endif
                    @else Total Due 
                    @endif </h6>
                <div class="table-responsive no-border">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Subtotal:</th>
                                <td class="text-right">৳ {{$payment_history_details->amount + 2000}}</td>
                            </tr>
                            <tr>
                                <th>Discount:</th>
                                <td class="text-right">
                                    @if (@$payment_history_details->paid_from == 2 && $payment_history_details->payment_system_id == 1)
                                    2000
                                    @else 
                                    0
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td class="text-right text-primary">
                                    <h5 class="text-semibold">৳ 
                                        {{$payment_history_details->amount}}
                                    </h5>
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if ($payment_history_details->is_running == 2)
                <div class="text-right">
                    <a href="{{route('paymentDetails', ['payment_history_id'=>$payment_history_details->id])}}" class="btn btn-primary btn-labeled"><b><i class="icon-cash3"></i></b> Pay Now</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- /modal with invoice -->