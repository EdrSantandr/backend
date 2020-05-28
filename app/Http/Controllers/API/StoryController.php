<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Story;

class StoryController extends Controller
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
            return Story::latest()->paginate(10);
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
        /*INICIO validation server for Sroty*/
		$this->validate($request,[
			'title' => 'required|string|max:191',
            'content' => 'required|string',
            'turns' => 'required|numeric|max:100'
        ]);
        /*FIN validation server for Story*/

        return Story::create([
            'title' => $request['title'],
            'content' => $request['content'],
            'turns' => $request['turns'],
            'date_ini' => \Carbon\Carbon::now(),
            'date_end' => \Carbon\Carbon::now(),
            'votes' => 0,
            'last_user_id' => 1,
            'state' => 'ini'
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
        $story = Story::findOrFail($id);
		
		$this->validate($request,[
			'title' => 'required|string|max:191',
            'content' => 'required|string',
            'turns' => 'required|numeric|max:100'
        ]);

		$story->update($request->all());
        return ['message'=>'Updates Story info'];
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
            $story = Story::findOrFail($id);            
            $story->delete();
            return ['message' => 'Type deleted'];
        }else
            return  response()->json(['error' => 'Error msg'], 404);
    }
    public function search()
    {
        if ($search = \Request::get('q')){
            $types= Story::where(function($query) use ($search){
                $query->where('title','LIKE',"%$search%")
                ->orWhere('state','LIKE',"%$search%")
                ->orWhere('content','LIKE',"%$search%");
            })->paginate(10);
        }else{
            $types=Story::latest()->paginate(10);
        }
        return $types;
    }
}
