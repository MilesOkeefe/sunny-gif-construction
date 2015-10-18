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