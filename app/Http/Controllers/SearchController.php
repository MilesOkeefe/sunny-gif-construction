<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller {

	public function searchPage(Request $request, $query=null){
		if(!$query) return view('pages.index');
		return view('pages.index')->with(['query' => $query]);
	}
}