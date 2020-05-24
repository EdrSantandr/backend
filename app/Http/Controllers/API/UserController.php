<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')){
            return User::latest()->paginate(5);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		/*INICIO validation server for User*/
		$this->validate($request,[
			'name' => 'required|string|max:191',
			'email' => 'required|string|max:191|unique:users',
			'password' => 'required|string|min:8'
		]);
		/*FIN validation server for User*/
        return User::create([
			'name' => $request['name'],
			'email' => $request['email'],
			'type' => $request['type'],
			'bio' => $request['bio'],
			'photo' => $request['photo'],
			'password' => Hash::make($request['password'])
		]);
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
    public function search()
    {
        if ($search = \Request::get('q')){
            $users= User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                ->orWhere('email','LIKE',"%$search%")
                ->orWhere('type','LIKE',"%$search%");
            })->paginate(5);
        }else{
            $users=User::latest()->paginate(5);
        }
        return $users;
    }
	/**For tue profile**/
	
	
	public function updateProfile(Request $request)
    {
        $user=auth('api')->user();
		
		$this->validate($request,[
			'name' => 'required|string|max:191',
			'email' => 'required|string|max:191|unique:users,email,'.$user->id,
			'password' => 'sometimes|required|min:8'
		]);
		
		$currentPhoto=$user->photo;
		if($request->photo != $currentPhoto){
			//upload
			$name= time().'.'.explode('/',explode(':',substr($request->photo,0, strpos($request->photo,';')))[1])[1];
			//Create an image manager
			\Image::make($request->photo)->save(public_path('img/profile/').$name);			
			$request->merge(['photo' =>$name]);
			//Delete the photo that we have
			$userPhoto=public_path('img/profile/').$currentPhoto;
			if (file_exists($userPhoto)){
				@unlink($userPhoto);
			}
		}
		/*check if passwrod change*/
		if (!empty($request->password)){
			$request->merge(['password' => Hash::make($request['password'])]);
		}
		
		$user->update($request->all());
		return ['message' => 'success'];
		//return $request->photo;
    }
	
	public function profile()
    {
        return auth('api')->user();
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
		$user = User::findOrFail($id);
		
		$this->validate($request,[
			'name' => 'required|string|max:191',
			'email' => 'required|string|max:191|unique:users,email,'.$user->id,
			'password' => 'sometimes|min:8'
        ]);
        /*check if passwrod change*/
		if (!empty($request->password)){
			$request->merge(['password' => Hash::make($request['password'])]);
		}

		$user->update($request->all());
        return ['message'=>'Update user info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->authorize('isAdmin')){
            $user = User::findOrFail($id);
            //delete the user
            $user->delete();
            return ['message' => 'User deleted'];
        }else
            return  response()->json(['error' => 'Error msg'], 404);
    }
}
