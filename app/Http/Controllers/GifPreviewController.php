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

            //if(!$saved) $output_file = env("BASE_DIR") . "data/season_10/video/4.webm"; //TODO: replace with example/404 webm
        }
        if(!file_exists($output_file)) return -1;
        return response()->download($output_file);
    }

    public function downloadEpisode($season, $episode){
        $file_dir = env("BASE_DIR") . "data/season_$season/video/$episode.webm";
        return response()->download($file_dir);
    }

    public function downloadWebM(Request $request, $season, $episode, $start_ms, $end_ms){
        /*$output_file = "/var/tmp/$season-$episode-$start_ms-$end_ms.webm";
        //if(!file_exists($output_file)){
            $ffmpeg = \FFMpeg\FFMpeg::create(array(
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
            ));
            //open the entire episode
            $video = $ffmpeg->open(env("BASE_DIR") . "data/season_$season/video_hq_webm/$episode.webm");

            //cut the video
            $start = max(floor($start_ms/1000), 0); //use max() to insure the start time isn't negative
            $duration = ceil($end_ms/1000) - floor($start_ms/1000);
            $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($start), \FFMpeg\Coordinate\TimeCode::fromSeconds($duration));

            //save the final video
            $saved = $video->save(new \FFMpeg\Format\Video\WebM(), $output_file);

            //if(!$saved) $output_file = env("BASE_DIR") . "data/season_10/video/4.webm"; //TODO: replace with example/404 webm
        //}
        $start_str = floor(($start_ms/1000)/60) . ":" . ($start_ms/1000) % 60;
        $end_str = floor(($end_ms/1000)/60) . ":" . ($end_ms/1000) % 60;
        $name = "Always_Sunny_S$season" . "E" . str_pad($episode,2,'0',STR_PAD_LEFT) . "_$start_str:$end_str.webm";
		$request->session()->put('webm-downloaded', "$season-$episode-$start_ms-$end_ms");
        if(!file_exists($output_file)) return -1;
        return response()->download($output_file, $name);*/
    }

    public function downloadGif(Request $request, $season, $episode, $start_ms, $end_ms){
    	/*$output_file = "/opt/www/data/test.gif";//"/var/tmp/$season-$episode-$start_ms-$end_ms.gif";
        //if(!file_exists($output_file)){
            $source_video = env("BASE_DIR") . "data/season_$season/video/$episode.webm";
            $start_time = max(floor($start_ms/1000), 0); //use max() to insure the start time isn't negative
            $duration = ceil($end_ms/1000) - floor($start_ms/1000);
            $uniq_id = uniqid();
            $palette = "/tmp/palette$uniq_id.png";
			$filters = "fps=15,scale=427:240:flags=lanczos";

			exec("ffmpeg -v warning -ss $start_time -t $duration -i $source_video -vf \"$filters,palettegen\" -y $palette");
			exec("ffmpeg -v warning -ss $start_time -t $duration -i $source_video -i $palette -lavfi \"$filters [x]; [x][1:v] paletteuse\" -y $output_file");
        //}
        $start_str = floor(($start_ms/1000)/60) . ":" . ($start_ms/1000) % 60 . "." . $start_ms % 1000;
        $end_str = floor(($end_ms/1000)/60) . ":" . ($end_ms/1000) % 60 . "." . $end_ms % 1000;
        $name = "Always_Sunny_S$season" . "E" . str_pad($episode,2,'0',STR_PAD_LEFT) . "_$start_str:$end_str.gif";
        $request->session()->put('gif-downloaded', "$season-$episode-$start_ms-$end_ms");
        if(!file_exists($output_file)) return -1;
        return response()->download($output_file, $name);*/
    }

    public function downloadFile(Request $request, $filetype, $season, $episode, $start_ms, $end_ms){
    	$output_file = "/var/tmp/$season-$episode-$start_ms-$end_ms.$filetype";
        //if(!file_exists($output_file)){
    		$video_folder = ($filetype == "gif")? "video" : "video_hq_webm";
            $source_video = env("BASE_DIR") . "data/season_$season/$video_folder/$episode.webm";
			$start_str = "00:" . str_pad(floor(($start_ms/1000)/60), 2, '0', STR_PAD_LEFT) . ":" . str_pad(($start_ms/1000) % 60, 2, '0', STR_PAD_LEFT) . "." . $start_ms % 1000;
			$end_str = "00:" . str_pad(floor(($end_ms/1000)/60), 2, '0', STR_PAD_LEFT) . ":" . str_pad(($end_ms/1000) % 60, 2, '0', STR_PAD_LEFT) . "." . $end_ms % 1000;

            //if($filetype == "gif"){
	            $uniq_id = uniqid();
	            $palette = "/tmp/palette$uniq_id.png";
				$filters = "fps=15,scale=427:240:flags=lanczos";
				$duration = ceil($end_ms/1000) - floor($start_ms/1000);


				$p_ex = shell_exec("ffmpeg -v warning -ss $start_str -t $duration -i $source_video -vf \"$filters,palettegen\" -y $palette");
				$ex = shell_exec("ffmpeg -v warning -ss $start_str -i $source_video -to $end_str -i $palette -lavfi \"$filters [x]; [x][1:v] paletteuse\" -y $output_file");
				//return "$p_ex\n$ex";
				file_put_contents("/var/log/ffmpeg.log", "p:\n$p_ex\nex:\n$ex", FILE_APPEND);
			//}else{
			//	exec("ffmpeg -ss $start_str -i $source_video -to $end_str -vcodec copy $output_file");
			//}
        //}
       
        $name = "Always_Sunny_S$season" . "E" . str_pad($episode,2,'0',STR_PAD_LEFT) . "($start_str)($end_str).$filetype";
        $request->session()->put("$filetype-downloaded", "$season-$episode-$start_ms-$end_ms");
        if(!file_exists($output_file)) return -1;
        return response()->download($output_file, $name);
    }

    public function fileHasDownloaded(Request $request, $file_type){ //$file_type is 'gif' or 'webm'
    	$season = $request->input('season');
    	$episode = $request->input('episode');
    	$start_ms = $request->input('start_ms');
    	$end_ms = $request->input('end_ms');

    	$token = $request->session()->get("$file_type-downloaded");
    	if($token == "$season-$episode-$start_ms-$end_ms"){
    		$request->session()->forget("$file_type-downloaded");
    		return "true";
    	}
    	return "false";
    }

}