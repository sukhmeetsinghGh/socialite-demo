<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => [
            'index', 'getUsers']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getUsers(Request $request)
    {
        if(\Auth::check())
        {
            $queryString = User::where('id', '!=', \Auth::id());
        }else{
            $queryString = new User;
        }

        if(!empty($request->input('search.value')))
        {
            $searchKey = $request->input('search.value');

            $queryString = $queryString->where(function($query) use($searchKey){
                $query->where('name','LIKE',"%{$searchKey}%")
                    ->orWhere('email','LIKE',"%{$searchKey}%")
                    ->orWhere('age',"{$searchKey}");
            });
        }
        $userDetails = $queryString->get();
        $tst = \DataTables::of($userDetails)
                ->editColumn('profile_picture',function($userDetails){
                    if(!empty($userDetails->profile_picture))
                    {
                        $imgUrl = asset('storage/uploads/'.$userDetails->profile_picture);
                    }else{
                        $imgUrl = asset('storage/uploads/dummy.jpg');
                    }    
                    return $imgUrl;
                })
                ->editColumn('age',function($userDetails){
                    return ($userDetails->age > 1) ? $userDetails->age.' Years' : 0;
                })
                ->editColumn('status',function($userDetails){
                    return $userDetails->status == 1 ? 'Active' : 'In-active';
                });
               if(\Auth::check())
               {

                $tst = $tst->addColumn('actions', function ($userDetails) {
                    $urlEdit = route('user.edit', $userDetails->id);
                    $urlDelete = route('user.delete', $userDetails->id);
                    $editString = '<a href="' . $urlEdit . '" class="btn btn-xs btn-primary"><i class="fa fa-edit" title="Edit"></i></a>';
                    $deleteString = '<a href="' . $urlDelete . '" class="btn btn-xs btn-primary delete"><i class="fa fa-trash" title="Delete"></i></a>';
                    return with($editString . ' ' . $deleteString);

                })->addIndexColumn()->rawColumns(['actions'])
                ->make(true);
              }else{
                $tst = $tst->addIndexColumn()->make(true);
              }
              return $tst;  
    }

    public function create()
    {
        $countries = \DB::table("countries")->select("id","name")->get();
        return view('create',compact('countries'));
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();
        try{
            $userDetails = new User();
            $userDetails->name = $request->get('name');
            $userDetails->email =$request->get('email');
            $userDetails->dob =$request->get('dob');
            $age = $this->getAge($request->get('dob'));
            $userDetails->age =$age;
            $userDetails->address =$request->get('address');
            $userDetails->country_id =$request->get('country_id');
            $userDetails->state_id =$request->get('states');
            $userDetails->city_id =$request->get('city_id');
            $userDetails->education =$request->get('education');
            if($request->file())
            {
                $fileName = time().'_'.$request->file('profile_picture')->getClientOriginalName();
                $filePath = $request->file('profile_picture')->storeAs('uploads', $fileName, 'public');
            $userDetails->profile_picture =$fileName;
            }

            $userDetails->status = $request->get('is_active');
            $userDetails->save();
             \DB::commit();
            return redirect()->route('user.index')->with('success', 'User Added Successfully');   
        }catch(\Exception $e)
        {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Error Creating User');
        }
    }

    public function edit($id)
    {
        try{
            $userDetails = User::findorFail($id);
            $countries = \DB::table("countries")->select("id","name")->get();
            $states = \DB::table("states")->select("id","name")->get();
            $cities = \DB::table("cities")->select("id","name")->get();
            return view('edit',compact('userDetails','countries','states','cities'));
        }catch(\Exception $e)
        {
            return redirect()->back()->with('error', 'Error User details not found');
        }    

    }

    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try{
            $userDetails = User::findorFail($id);
            $userDetails->name = $request->get('name');
            $userDetails->email =$request->get('email');
            $userDetails->dob =$request->get('dob');
            $age = $this->getAge($request->get('dob'));
            $userDetails->age =$age;
            $userDetails->address =$request->get('address');
            $userDetails->country_id =$request->get('country_id');
            $userDetails->state_id =$request->get('states');
            $userDetails->city_id =$request->get('city_id');
            $userDetails->education =$request->get('education');
            
            if($request->file())
            {
                $fileName = time().'_'.$request->file('profile_picture')->getClientOriginalName();
                $filePath = $request->file('profile_picture')->storeAs('uploads', $fileName, 'public');
            $userDetails->profile_picture =$fileName;
            }

            $userDetails->status = $request->get('is_active');
            $userDetails->save();
             \DB::commit();
            return redirect()->route('user.index')->with('success', 'User Added Successfully');   
        }catch(\Exception $e)
        {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Error Creating User');
        }
    }

    public function delete($id)
    {
        \DB::beginTransaction();
        try{

            User::findOrFail($id)->delete();
            \DB::commit();
            return redirect()->route('user.index')->with('success', 'User Deleted Successfully');
        }catch(\Exception $e){
            \DB::rollBack();
            return redirect()->back()->with('error', 'Error User details not found');
        }
    }

    public function getStates($countryId)
    {
        $states = \DB::table("states")->select("id","name")
            ->where("country_id",$countryId)->get();
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = \DB::table("cities")->select("id","name")
            ->where("state_id",$stateId)->get();
        return response()->json($cities);    
    }

    private function getAge($dob)
    {
        $bday = new \DateTime($dob); // Your date of birth
        $today = new \Datetime(date('m.d.y'));
        $diff = $today->diff($bday);
        return $diff->y;
    }
}
