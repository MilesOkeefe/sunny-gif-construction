@extends('layouts.master')

@section('styles')
	<link rel="stylesheet" href="/css/app.css">
@stop

@section('body')
	<div class="hero-wrapper valign-wrapper">
		<div class="hero valign">
			<div class="hero-background"></div>
			<div class="search-area">
				<div class="search-wrapper">
					<div class="input-wrapper {{ !empty($query)? 'interacted' : '' }}">
						<span class="search-input" contenteditable="true">{{ !empty($query)? urldecode($query) : 'Type any quote from Season 10' }}</span>
					</div>
				</div>
				<div class="area-below-search">
					<div class="buttons">
						<div class="btn btn-red search-btn"><i class="material-icons left">search</i>Search</div>
						<div class="btn btn-black random-btn">Random Quote</div>
					</div>
					<div class="search-results">
						<div class="loader">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
								<rect x="0" y="0" width="4" height="20">
									<animate attributeName="opacity" attributeType="XML" values="1; .2; 1" begin="0s" dur="0.6s" repeatCount="indefinite" />
								</rect>
								<rect x="7" y="0" width="4" height="20">
									<animate attributeName="opacity" attributeType="XML" values="1; .2; 1"  begin="0.2s" dur="0.6s" repeatCount="indefinite" />
								</rect>
								<rect x="14" y="0" width="4" height="20">
									<animate attributeName="opacity" attributeType="XML" values="1; .2; 1"  begin="0.4s" dur="0.6s" repeatCount="indefinite" />
								</rect>
							</svg>
						</div>
						<div class="none-found">
							<img src="/images/404/{{ rand(1,10) }}.gif">
							<h4>No quotes found</h4>
						</div>
						<div class="results">
							
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script src="/js/fuse.min.js"></script>
	<script src="/js/subs.js"></script>
	<script src="/js/index.js"></script>
	@if(!empty($query))
	<script>
		$(function(){
			$(".search-btn").trigger('click');
		});
	</script>
	@endif
@stop