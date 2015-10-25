@extends('layouts.master')

@section('styles')
	<link rel="stylesheet" href="/css/edit.css">
@stop

@section('body')
	<div class="content-wrapper">
		<div class="edit-area">
			<a class="btn btn-yellow back-btn" href="/search/{{ $last_query }}"><i class="material-icons left">keyboard_backspace</i>Back</a>
			<div class="gif-wrapper">
				<div class="video-overlay">
					<div class="toggle-btn pause-btn">
						<i class="material-icons medium btn-black">pause</i>
					</div>
					<div class="toggle-btn play-btn">
						<i class="material-icons medium btn-red">play_arrow</i>
					</div>
				</div>
				<video src="/episode/{{$season}}/{{$episode}}" poster="/images/loading.gif" preload muted loop></video>
			</div>
		</div>
		<div class="base">
			<div class="bottom-area">
				<div class="bottom-area-inner">
					<div class="bottom-area-center">
						<div class="video-controls">
							<input class="timeline-before"	type="range" min="-130"	value="0"	max="{{ 426/2 }}"	step="1">
							<input class="timeline"			type="range" min="0"	value="0"	max="426"	step="1">
							<input class="timeline-after"	type="range" min="{{ 426/2 }}"	value="426"	max="{{ 426+130 }}"	step="1">
						</div>
						<div class="video-base">
							<div class="tool-row">
								<div class="btn btn-black replay-btn"><i class="material-icons left">replay</i> replay</div>
								<div class="dl-btns">
									<a class="btn btn-red dl-gif-btn" href="/gif/{{ $season }}/{{ $episode }}/{{ $start_ms }}-{{ $end_ms }}"><i class="material-icons left">file_download</i> GIF</a>
									<a class="btn btn-red dl-webm-btn" href="/webm/{{ $season }}/{{ $episode }}/{{ $start_ms }}-{{ $end_ms }}"><i class="material-icons left">file_download</i> WebM</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="hero-background"></div>
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script>
		var padding = {{ $padding }};
		var startTimeMS = {{ $start_ms }};
		var endTimeMS = {{ $end_ms }};
		var duration = {{ $duration }};
		var season = {{ $season }};
		var episode = {{ $episode }};
	</script>
	<script src="/js/edit.js"></script>
@stop