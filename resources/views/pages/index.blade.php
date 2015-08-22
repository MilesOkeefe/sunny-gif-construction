@extends('layouts.master')

@section('styles')
	<link rel="stylesheet" href="/css/app.css">
@stop

@section('body')
	<div class="hero-wrapper valign-wrapper">
		<div class="hero valign">
			<img class="hero-background" src="/images/promo.jpg">
			<div class="search-wrapper">
				<div class="input-wrapper">
					<span class="search-input" contenteditable="true">Search any quote from Season 10</span>
				</div>
			</div>
		</div>
	</div>
@stop