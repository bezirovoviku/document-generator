<?php namespace App\Http\Controllers;

class DocsController extends Controller {
	public function index()
	{
		return view('docs.index');
	}

	public function templates()
	{
		return view('docs.templates');
	}
}
