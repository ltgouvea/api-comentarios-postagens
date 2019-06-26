<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user = Auth::user();
        $input = $request->all();

        $input['login'] = $user->email;
        $input['assinante'] = $user->is_assinante;

        $comentario = Comentario::create($input);

        return $this->sendResponse($comentario, 'Comentário criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function show(Comentario $comentario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function edit(Comentario $comentario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comentario $comentario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comentario $comentario)
    {
        //
    }
}
