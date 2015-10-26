<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditController extends Controller {

	public function editPage(Request $request, $season, $episode, $start_ms, $end_ms){
		$FPS = 24;
		$padding = 1*$FPS;
		$duration = (($end_ms-$start_ms)/1000)*$FPS;
		$TIMELINE_WIDTH_PX = 426;
		$padding_width_px = ($TIMELINE_WIDTH_PX * $padding)/$duration;
		$vars = [
			'season' => $season,
			'episode' => $episode,
			'start_ms' => $start_ms,
			'end_ms' => $end_ms,
			'padding' => $padding,
			'padding_width_px' => $padding_width_px,
			'duration' => $duration
		];
		return view('pages.edit', $vars);
	}

}

?>