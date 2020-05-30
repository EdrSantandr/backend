<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Group;

class GroupController extends Controller
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
        if (\Gate::allows('isAdmin')){
            return Group::latest()->paginate(5);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function search()
    {
        if ($search = \Request::get('q')){
            $groups= Group::where(function($query) use ($search){
                $query->where('title','LIKE',"%$search%")
                ->orWhere('description','LIKE',"%$search%");
            })->paginate(5);
        }else{
            $groups=Group::latest()->paginate(5);
        }
        return $groups;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
			'title' => 'required|string',
            'description' => 'required'
        ]);
        /*FIN validation server for Story*/
        $name= time().'.'.explode('/',explode(':',substr($request->photo,0, strpos($request->photo,';')))[1])[1];
			//Create an image manager
		\Image::make($request->photo)->save(public_path('img/groups/').$name);
		$request->merge(['photo' =>$name]);

        return Group::create([
            'title' => $request['title'],
            'description' => $request['content'],
            'photo' => $name,
            'url'=>$request['url']
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $this->validate($request,[
			'title' => 'required|string',
            'description' => 'required'
        ]);

        $group = Group::findOrFail($id);
        /*FIN validation server for Story*/
        if (!empty($request->photo)){
            $name= time().'.'.explode('/',explode(':',substr($request->photo,0, strpos($request->photo,';')))[1])[1];
			//Create an image manager
		    \Image::make($request->photo)->save(public_path('img/groups/').$name);
            $request->merge(['photo' =>$name]);
        }
        $group->update($request->all());
        return ['message' => 'success'];
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
            $group = Group::findOrFail($id);
            //delete the user
            $group->delete();
            return ['message' => 'User deleted'];
        }else
            return  response()->json(['error' => 'Error msg'], 404);
    }
}
