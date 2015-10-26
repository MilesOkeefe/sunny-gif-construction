$(function(){
	var $video = $("video");
	var video = $("video").get(0); //get native DOM element to interact with video functions
	var $videoOverlay = $(".video-overlay");
	var videoCanPlay = false;
	var $timeline = $(".timeline");
	var timeline = $(".timeline").get(0);
	var timelineBefore = $(".timeline-before").get(0);
	var timelineAfter = $(".timeline-after").get(0);
	var timelineWidth = $timeline.outerWidth();
	var OG_timelineWidth = timelineWidth;
	startTimeMS = (startTimeMS)? startTimeMS : 0;
	endTimeMS = (endTimeMS)? endTimeMS : 3;
	var OG_startTimeMS = startTimeMS;
	var OG_endTimeMS = endTimeMS;
	var pausedLoopID = null; //intervalID for the UI loop when the video is paused

	video.oncanplay = function(){
		videoCanPlay = true;
	};

	video.currentTime = startTimeMS/1000;
	playVideo();

	video.addEventListener('timeupdate', function(){
		if(video.currentTime > endTimeMS/1000){
			video.currentTime = startTimeMS/1000;
		}
	});

	function updateTimeline() {
		if(!video.paused){
			var value = (video.currentTime - startTimeMS/1000)/((endTimeMS-startTimeMS)/1000)*timelineWidth;
			timeline.value = value;
			/*$("#timeline-value").text(Math.round(value));
			$("#timeline-max").text(timelineWidth);*/

			//wait approximately 16ms and run again
			timelineChange();
			requestAnimationFrame(updateTimeline);
		}
	}
	updateTimeline();

	function playVideo(){
		video.play();
		if(pausedLoopID) clearInterval(pausedLoopID);
	}

	function pauseVideo(){
		video.pause();
		pausedLoopID = setInterval(timelineChange, 15);
	}

	timeline.addEventListener("change", function(){
		var time = OG_startTimeMS + timeline.value/426;
		video.currentTime = time/1000; //update the video time
		//console.log("change");
	});

	/*$(".timeline").mousedown(function(e){
		var parentOffset = $(this).parent().offset();
   		var relX = e.pageX - parentOffset.left;
		console.log("mousedown: " + relX);
		video.pause();
		timeline.value = relX;
		video.play();
	});*/

	$video.click(function(){
		if(!videoCanPlay) return false;
		video.paused ? playVideo() : pauseVideo();
		if(!video.paused){
			$videoOverlay.hide(); //hide overlay so that toggle button doesn't appear
			updateTimeline();
		}
	});

	$video.hover(function(){
		if(!videoCanPlay) return false;
		$videoOverlay.show();
		$(".toggle-btn").hide();
		if(video.paused){
			$(".play-btn").show();
		}else{
			$(".pause-btn").show();
		}
	});

	$videoOverlay.mouseout(function(){
		$videoOverlay.hide();
	});

	//allow play/pause button on video overlay to trickle action down to actual video element
	$videoOverlay.click(function(){
		$video.trigger('hover');
		$video.trigger('click');
	});

	//replay the GIF from the beginning (which is the user-defined beginning, not 00:00) 
	$(".replay-btn").click(function(){
		video.currentTime = startTimeMS/1000;
		if(video.paused){
			playVideo();
			updateTimeline();
		}
	});

	function timelineChange(){
		var TIME_TO_PX = 1;//1.6; //ratio to convert time to px on timeline elements
		var beforeLeft = timelineBefore.value;
		var extraWidth = (timelineAfter.value - OG_timelineWidth);
		var width = OG_timelineWidth + -1*beforeLeft + extraWidth;
		$timeline.css({"left": beforeLeft + "px", "width": width + "px"});

		timelineWidth = width;

		var padding = 50;
		startTimeMS = OG_startTimeMS + (timelineBefore.value/padding)*1000; //add before value since it is negative
		endTimeMS = OG_endTimeMS + ((timelineAfter.value-OG_timelineWidth)/padding)*1000;

		timeline.max = timelineWidth;
	}

	timelineBefore.addEventListener("change", updateURL);
	timelineAfter.addEventListener("change", updateURL);

	function updateURL(){
		var new_url_end = season + "/" + episode + "/" + Math.floor(startTimeMS) + "-" + Math.ceil(endTimeMS);
		$(".dl-gif-btn").attr('href', "/gif/" + new_url_end);
		$(".dl-webm-btn").attr('href', "/webm/" + new_url_end);
		history.replaceState(null, null, "/edit/" + new_url_end);
	}

	function checkGIFDownload(){
		$.post('/gif/has-downloaded', {season:season, episode:episode, start_ms:startTimeMS, end_ms:endTimeMS})
		.done(function(data){
			if(data == "true"){
				$(".dl-gif-btn").removeClass('loading');
			}else{
				setTimeout(checkGIFDownload, 1000);
			}
		});
	}
	$(".dl-gif-btn").click(function(e){
		if($(this).hasClass('loading')) return e.preventDefault();
		$(this).addClass('loading');
		setTimeout(checkGIFDownload(), 2000);
	});

	function checkWebMDownload(){
		$.post('/webm/has-downloaded', {season:season, episode:episode, start_ms:startTimeMS, end_ms:endTimeMS})
		.done(function(data){
			if(data == "true"){
				$(".dl-webm-btn").removeClass('loading');
			}else{
				setTimeout(checkWebMDownload, 1000);
			}
		});
	}
	$(".dl-webm-btn").click(function(e){
		if($(this).hasClass('loading')) return e.preventDefault();
		$(this).addClass('loading');
		setTimeout(checkWebMDownload(), 2000);
	});

	$(".back-btn").attr('href', '/search/' + Cookies.get('last-query'));
});