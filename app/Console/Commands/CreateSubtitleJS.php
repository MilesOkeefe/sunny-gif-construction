<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Captioning\Format\SubripFile;

class CreateSubtitleJS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subtitles:create_js_file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates static JSON file of all subtitles.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $output = [];
        $histography = [
            10 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        ];
        foreach($histography as $season => $episodes){
            foreach($episodes as $episode){
                $srt = new SubripFile("/opt/www/data/season_$season/subs/$episode.srt");
                $cues = $srt->getCues();

                foreach($cues as $cue){
                    $text = strip_tags($cue->getText());
                    $start = $cue->getStartMS();
                    $stop = $cue->getStopMS();
                    if(strpos($text, ":[GWC]:") !== false || strpos($text, "Sync & corrections by honeybunny") !== false) continue;
                    $output[] = ['text'=>$text, 'start'=>$start, 'stop'=>$stop, 'season'=>$season, 'episode'=>$episode];
                }
            }
        }
        $output_str = "var subtitles = " . json_encode($output) . ";";
        file_put_contents("/opt/www/sunny/public/js/subs.js", $output_str);
    }
}
