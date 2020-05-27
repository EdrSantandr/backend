<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Type;

class TypeController extends Controller
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
            return Type::latest()->paginate(5);
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
			'name' => 'required|string|max:191'
		]);
		/*FIN validation server for User*/
        return Type::create([
			'name' => $request['name']
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
        $type = Type::findOrFail($id);
		
		$this->validate($request,[
			'name' => 'required|string|max:191'
        ]);
		$type->update($request->all());
        return ['message'=>'Updates type info'];
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
            $type = Type::findOrFail($id);
            //delete the user
            $type->delete();
            return ['message' => 'Type deleted'];
        }else
            return  response()->json(['error' => 'Error msg'], 404);
    }

    public function search()
    {
        if ($search = \Request::get('q')){
            $types= Type::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%");                
            })->paginate(5);
        }else{
            $types=Type::latest()->paginate(5);
        }
        return $types;
    }
}
