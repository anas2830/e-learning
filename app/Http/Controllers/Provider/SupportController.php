<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Helper;
use File;
use App\Models\EduSupport_Provider;
use App\Models\EduSupportCategory_Provider;
use App\Models\EduAssingedSupportCategory_Provider;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['all_supports'] = $all_supports = EduSupport_Provider::valid()->latest()->get();
        foreach ($all_supports as $key => $support) {
            $support->support_cate_names = EduAssingedSupportCategory_Provider::join('edu_support_categories', 'edu_support_categories.id', '=', 'edu_assinged_support_categories.support_category_id')
            ->select('edu_support_categories.category_name')
            ->where('edu_assinged_support_categories.support_id', $support->id)
            ->where('edu_assinged_support_categories.valid', 1)
            ->get();
        }
        return view('provider.support.listData', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = EduSupportCategory_Provider::valid()->latest()->get();
        return view('provider.support.create', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                => 'required',
            'email'               => 'unique:App\Models\EduSupports,email',
            'phone'               => 'required',
            'password'            => 'required',
            'support_category_id' => 'required',
        ]);
        if ($validator->passes()) {
            $supportInfo = EduSupport_Provider::create([
                'support_id'     => Helper::generateAutoID('edu_supports','support_id'),
                'name'           => $request->name,
                'address'        => $request->address,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'phone'          => $request->phone,
                'email_verified' => 1,
            ]);
            $support_category_id_arr = $request->support_category_id;
            $filter_category_ids = array_filter($support_category_id_arr);
            foreach($filter_category_ids as $key => $cate_id) 
            {
                EduAssingedSupportCategory_Provider::create([
                    'support_id'          => $supportInfo->id,
                    'support_category_id' => $cate_id
                ]);
            }
            $output['messege'] = 'Support has been created';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['support'] = EduSupport_Provider::valid()->find($id);
        $data['categories'] = EduSupportCategory_Provider::valid()->latest()->get();
        $data['assigned_category_ids'] = EduAssingedSupportCategory_Provider::valid()->where('support_id', $id)->get()->pluck('support_category_id')->toArray();
        return view('provider.support.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'support_category_id' => 'required',
            'name'                => 'required',
            'email'               => 'unique:App\Models\EduSupports,email,'.$id,
            'phone'               => 'required'
        ]);
        $old_support_categoris = EduAssingedSupportCategory_Provider::valid()->where('support_id', $id)->get()->keyBy('support_category_id')->all();

        if ($validator->passes()) {

            if(!empty($request->password)){
                EduSupport_Provider::find($id)->update([
                    'category_id' => $request->category_id,
                    'name'        => $request->name,
                    'address'     => $request->address,
                    'email'       => $request->email,
                    'password'    => Hash::make($request->password),
                    'phone'       => $request->phone,
                ]);

                $support_category_id_arr = $request->support_category_id;
                $new_category_ids = array_filter($support_category_id_arr);
                foreach($old_support_categoris as $key => $oldValue) {
                    if(!in_array($key, $new_category_ids)) {
                        EduAssingedSupportCategory_Provider::find($oldValue->id)->delete();
                    }
                }
                foreach($new_category_ids as $key => $cate_id) 
                {
                    if(!array_key_exists($cate_id, $old_support_categoris)) {
                        EduAssingedSupportCategory_Provider::create([
                            'support_id'          => $id,
                            'support_category_id' => $cate_id
                        ]);
                    }
                }

            }else{
                EduSupport_Provider::find($id)->update([
                    'category_id' => $request->category_id,
                    'name'        => $request->name,
                    'address'     => $request->address,
                    'email'       => $request->email,
                    'phone'       => $request->phone,
                ]);

                $support_category_id_arr = $request->support_category_id;
                $new_category_ids = array_filter($support_category_id_arr);
                foreach($old_support_categoris as $key => $oldValue) {
                    if(!in_array($key, $new_category_ids)) {
                        EduAssingedSupportCategory_Provider::find($oldValue->id)->delete();
                    }
                }
                foreach($new_category_ids as $key => $cate_id) 
                {
                    if(!array_key_exists($cate_id, $old_support_categoris)) {
                        EduAssingedSupportCategory_Provider::create([
                            'support_id'          => $id,
                            'support_category_id' => $cate_id
                        ]);
                    }
                }
            }
            
            $output['messege'] = 'Support has been updated';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supportInfo = EduSupport_Provider::valid()->find($id);
        $assigned_categories = EduAssingedSupportCategory_Provider::valid()->where('support_id', $id)->get();
        if (count($assigned_categories) > 0) {
            foreach ($assigned_categories as $key => $category) {
                EduAssingedSupportCategory_Provider::valid()->find($category->id)->delete();
            }
        }
        EduSupport_Provider::valid()->find($id)->delete();
    }
}
