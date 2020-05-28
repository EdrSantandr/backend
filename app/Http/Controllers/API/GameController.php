<?php

namespace App\Http\Controllers\API;

use App\Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameController extends Controller
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
            return Game::latest()->paginate(10);
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
        /*INICIO validation server for Game*/
		$this->validate($request,[
			'title' => 'required|string|max:191',
            'description' => 'required',
            'time_min' => 'required|numeric|min:20000|max:40000',//20 seg | 40 seg
            'time_max' => 'required|numeric|min:40000|max:200000', //40 seg | 200 seg
            'characters_min' => 'required|numeric|min:10|max:1000',
            'characters_max' => 'required|numeric|min:1000|max:10000'
        ]);
        /*FIN validation server for Game*/

        return Game::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'time_min' => $request['time_min'],
            'time_max' => $request['time_max'],
            'characters_min' => $request['characters_min'],
            'characters_max' => $request['characters_max']            
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
        $game = Game::findOrFail($id);
		
		$this->validate($request,[
			'title' => 'required|string|max:191',
            'description' => 'required',
            'time_min' => 'required|numeric|min:20000|max:40000',//20 seg | 40 seg
            'time_max' => 'required|numeric|min:40000|max:200000', //40 seg | 200 seg
            'characters_min' => 'required|numeric|min:10|max:1000',
            'characters_max' => 'required|numeric|min:1000|max:10000'
        ]);

		$game->update($request->all());
        return ['message'=>'Updates Games info'];
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
            $game = Game::findOrFail($id);
            //delete the user
            $game->delete();
            return ['message' => 'Game deleted'];
        }else
            return  response()->json(['error' => 'Error msg'], 404);
    }
    public function search()
    {
        if ($search = \Request::get('q')){
            $games= Game::where(function($query) use ($search){
                $query->where('title','LIKE',"%$search%")
                ->orWhere('description','LIKE',"%$search%");                
            })->paginate(10);
        }else{
            $games=Game::latest()->paginate(10);
        }
        return $games;
    }
}
