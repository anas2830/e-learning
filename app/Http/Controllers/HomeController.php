<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EduRecentVideo_Web;
use App\Models\EduStudentReview_Web;
use App\Models\EduPhotoGallery_Web;
use App\Models\EduCompanyWorkFlow_Web;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('web.home');
    }
    
    public function home()
    {
        return view('web.home');
    }
    
    
}
