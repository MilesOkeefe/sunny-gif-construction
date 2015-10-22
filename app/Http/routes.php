<?php

Route::get('/', function(){
	return view('pages.index');
});
Route::get('/search/{query}', function($query){
	return view('pages.index')->with(['query' => $query]);
});

Route::get('/thumbnail/{season}/{episode}/{start_ms}-{end_ms}', 'GifPreviewController@downloadThumbnail');

Route::get('/edit/{season}/{episode}/{start_ms}-{end_ms}', 'EditController@editPage');

Route::get('/episode/{season}/{episode}', 'GifPreviewController@downloadEpisode');
Route::get('/gif/{season}/{episode}/{start_ms}-{end_ms}', 'GifPreviewController@downloadGif');
Route::get('/webm/{season}/{episode}/{start_ms}-{end_ms}', 'GifPreviewController@downloadWebM');
Route::post('/{file_type}/has-downloaded', 'GifPreviewController@fileHasDownloaded')->where('file_type', 'gif|webm');

Route::post('/github-webhook/{key}', function($key){
	if($key == env("WEBHOOK_KEY") && $_POST['payload']){
		$git_dir = env("BASE_DIR") . "sunny/";
		shell_exec("cd $git_dir && git reset --hard HEAD && git pull");
	}
});