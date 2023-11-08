<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function index()
{
    return view('pages.home');
}

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showCreateProjectForm()
    {
        return view('pages.createProject');
    }
    public function store(Request $request)
{
    // Lógica para criar o projeto com os dados do formulário aqui.

    // Após criar o projeto, você pode redirecionar para a página desejada, por exemplo:
    return redirect()->route('project.store')->with('success', 'Projeto criado com sucesso.');
}



}


