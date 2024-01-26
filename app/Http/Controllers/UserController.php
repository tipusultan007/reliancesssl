<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function usersData(Request $request)
    {

        $totalData = User::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        if(empty($request->input('search.value')))
        {
            $posts = User::offset($start)
                ->limit($limit)
                ->orderBy('name','asc')
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts =  User::where('name', 'LIKE',"%{$search}%")
                ->orWhere('phone', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('name','asc')
                ->get();

            $totalFiltered = User::where('name', 'LIKE',"%{$search}%")
                ->orWhere('phone', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                if ($post->photo=="")
                {
                    $photo = '<img src="'.asset('assets/images/users/avatar-1.jpg').'" height="60" class="rounded-circle">';
                }else{
                    $photo = '<img src="'.asset('assets/images/users/').$post->photo.'" height="60" class="rounded-circle">';
                }
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['photo'] = $photo;
                $nestedData['phone'] = $post->phone??'';
                $nestedData['email'] = $post->email??'';
                $nestedData['status'] = $post->status??'';
                $nestedData['address'] = $post->address??'';
                $nestedData['join_date'] = date('j M Y h:i a',strtotime($post->join_date));
                $nestedData['action'] = '<div class="dropdown float-end text-muted">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <!-- item-->
                                                        <a href="'.route('users.show',$post->id).'" class="dropdown-item">View</a>
                                                        <a href="'.route('users.edit',$post->id).'" class="dropdown-item">Edit</a>
                                                        <a href="javascript:void(0);" class="dropdown-item">Delete</a>
                                                    </div>
                                                </div>';
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        if ($request->hasFile('nid')) {
            $nidName = "nid-".date('dmYHis').'.'.$request->nid->extension();
            $request->nid->move(public_path('uploads'), $nidName);
            $input['nid'] = $nidName;
        }
        if ($request->hasFile('photo')) {
            $photoName = "photo-".date('dmYHis').'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $photoName);
            $input['photo'] = $photoName;
        }
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','User deleted successfully');
    }
}
