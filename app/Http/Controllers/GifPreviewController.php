<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Input;
use App\Http\Controllers\Controller;

class GifPreviewController extends Controller {

    public function downloadThumbnail($season, $episode, $start_ms, $end_ms){
        $time_ms = $start_ms + floor(($end_ms-$start_ms)/2); //halfway between start and end
        $output_file = env("BASE_DIR") . "data/thumbnails/$season-$episode-$time_ms.jpg";
        if(!file_exists($output_file)){
            $source_video = env("BASE_DIR") . "data/season_$season/video/$episode.webm";
            $timestamp = $time_ms/1000;
            $command = "ffmpeg -ss $timestamp -i $source_video -frames:v 1 $output_file";
            exec($command);
        }
        if(!file_exists($output_file)) return -1;
        return response()->download($output_file);
    }

    public function downloadEpisode($season, $episode){
        $file_dir = env("BASE_DIR") . "data/season_$season/video/$episode.webm";
        return response()->download($file_dir);
    }

    public function downloadFile(Request $request, $filetype, $season, $episode, $start_ms, $end_ms){
    	$output_file = "/var/tmp/$season-$episode-$start_ms-$end_ms.$filetype";
        if(!file_exists($output_file)){
    		$video_folder = ($filetype == "gif")? "video" : "video_hq_webm";
            $source_video = env("BASE_DIR") . "data/season_$season/$video_folder/$episode.webm";
			$start_str = "00:" . str_pad(floor(($start_ms/1000)/60), 2, '0', STR_PAD_LEFT) . ":" . str_pad(($start_ms/1000) % 60, 2, '0', STR_PAD_LEFT) . "." . $start_ms % 1000;
			$end_str = "00:" . str_pad(floor(($end_ms/1000)/60), 2, '0', STR_PAD_LEFT) . ":" . str_pad(($end_ms/1000) % 60, 2, '0', STR_PAD_LEFT) . "." . $end_ms % 1000;
			$duration = ceil($end_ms/1000) - floor($start_ms/1000);

            if($filetype == "gif"){
	            $uniq_id = uniqid();
	            $palette = "/tmp/palette$uniq_id.png";
	            $clip = "/tmp/$uniq_id.webm";
				$filters = "fps=15,scale=427:240:flags=lanczos";

				shell_exec("ffmpeg -ss $start_str -t $duration -i $source_video -vf \"$filters,palettegen\" -y $palette 2>&1");
				shell_exec("ffmpeg -i $source_video -ss $start_str -t $duration -vcodec copy $clip 2>&1");
				shell_exec("ffmpeg -i $clip -i $palette -lavfi \"$filters [x]; [x][1:v] paletteuse\" -y $output_file 2>&1");
			}else{
				shell_exec("ffmpeg -i $source_video -ss $start_str -t $duration -vcodec copy $output_file 2>&1");
			}
        }
       
        $name = "Always_Sunny_S$season" . "E" . str_pad($episode,2,'0',STR_PAD_LEFT) . "($start_str)($end_str).$filetype";
        $request->session()->put("$filetype-downloaded", "$season-$episode-$start_ms-$end_ms");
        if(!file_exists($output_file)) return -1;
        return response()->download($output_file, $name);
    }

    public function fileHasDownloaded(Request $request, $filetype){ //$filetype is 'gif' or 'webm'
    	$season = $request->input('season');
    	$episode = $request->input('episode');
    	$start_ms = $request->input('start_ms');
    	$end_ms = $request->input('end_ms');

    	$token = $request->session()->get("$filetype-downloaded");
    	if($token == "$season-$episode-$start_ms-$end_ms"){
    		$request->session()->forget("$filetype-downloaded");
    		return "true";
    	}
    	return "false";
    }

}