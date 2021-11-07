<?php

namespace App\Http\Middleware;

use DB;
use Auth;
use Closure;
use Illuminate\Http\Request;
use App\Models\EduAssignBatchStudent_User;

class CourseStudentFreez
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $assign_batch_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();

        if(!empty($assign_batch_info)) {
            $is_freez = $assign_batch_info ->is_freez;
            $freez_reason = $assign_batch_info ->freez_reason;
            if($is_freez == 1) {
                // $data['messege'] = $freez_reason;
                // $data['back_route'] = "home";
                // return response()->view('examError', $data);
                return redirect()->route('home');
            } else {
                return $next($request);
            }
        }
    }
}
