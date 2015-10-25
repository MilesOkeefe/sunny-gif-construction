<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller {

	public function searchPage(Request $request, $query){
		$request->session()->put('last-query', $query);
		return view('pages.index')->with(['query' => $query]);
	}
}