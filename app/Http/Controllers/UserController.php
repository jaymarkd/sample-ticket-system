<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //web routes
    public function index(Request $request){
        // var_dump($request->query());
        $searchbox = $request->query('searchbox');
        if($searchbox == ''){
            $users = User::all();
        }else{
            $users = User::where('name','like','%'.$searchbox.'%')->get();
        }
        return view('users.index' , ['users'=>$users]);
    }

    public function store(Request $request){

        var_dump($request->input());
        $name = $request->input('name');
        $email = $request->input('email');
        $role = $request->input('role');
        $password = Hash::make($request->input('password'));
        var_dump([$name,$email,$password,$role]);

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->role = $role;
        $user->password = $password;

        $user->save();
        return Redirect::route('users.index');
        // $name = $request->input('name');
        //
    }

    public function create(){
        return view('users.create');
    }

    public function show($id, Request $request){
        $user = User::where('id', $id)->first();
        // var_dump($book);
        // var_dump($id);
        return view('users.show',['user'=>$user]);
    }

    public function update($id, Request $request){
        // var_dump($id);
        // var_dump($request->input());
        $user = User::find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        if($request->password != ''){
        $user->password = Hash::make($request->password);
        }
        $user->save();

        // die();
        return Redirect::route('users.index');
    }

    public function destroy($id, Request $request){
        User::destroy($id);
        var_dump($id);
        return Redirect::route('users.index');

    }

    //api
    public function apiGetAll()
    {
        $users = User::all();

        return response()->json($users, 200);
    }

    //api get one
    public function apiGetOne($id)
    {

        try {

            $user = User::where('id',$id)->firstOrFail();

        } catch (\Throwable $th) {

             return response()->json(['User Not Found'],404);
        }

        return response()->json($user, 200);
    }

    //api create
    public function apiCreateUser(Request $request)
    {
        // dd(1);
        $validator = Validator::make($request->all(),[
          'email' => 'required | email | max:200 | unique:users,email',
          'name' => 'required | max:100',
          'role' => 'required | in:member,Admin ',
          'password' => 'required | max:5'
        ]);

        if($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }

        $data = $request->only([
            'name',
            'email',
            'role',
            'password'
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if($user) {
            $responseData = [
                'status'=> 'success',
                'message' => 'User Created'
            ];
            return response()->json($responseData,200);

        }else {
            return response()->json('Unable to create user',404);
        }

    }

    public function profile(Request $request){
        return $request->user();
    }

}
