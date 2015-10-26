//highlights for selection all text in an element
jQuery.fn.selectText = function(){
   var element = this[0];
   setTimeout(function(){
	   if(document.body.createTextRange){
	       var range = document.body.createTextRange();
	       range.moveToElementText(element);
	       range.select();
	   }else if (window.getSelection){
	       var selection = window.getSelection();        
	       var range = document.createRange();
	       range.selectNodeContents(element);
	       selection.removeAllRanges();
	       selection.addRange(range);
	   }
	}, 1);
};

jQuery.fn.setCaretPosition = function(caretPos){
	var elem = this[0];
	console.debug(elem);
	if(elem != null){
		//console.debug(caretPos);
		if(elem.createTextRange){
			var range = elem.createTextRange();
			range.move('character', caretPos);
			range.select();
		}else{
			if(elem.selectionStart){
				elem.focus();
				elem.setSelectionRange(caretPos, caretPos);
			}else{
				elem.focus();
			}
		}
	}
};

var season_10_quotes = [
	"All right all right all right",
	"LOOK AT ME WHEN YOU’RE TALKING TO ME",
	"I'm a five star man",
	"This doesn't represent me",
	"Good fish, good solid fish?",
	"Oh god, don't be a dumb hungry bitch the entire time",
	"It makes sense, don't be a bitch",
	"I don't have online though",
	"He's gonna put all the brains in my head", /* subtitled */
	"I'm playing both sides", /* cut off */
];
$(function(){
	var $input = $(".search-input");
	var searchPlaceholderText = $input.text();
	$input.focus(function(){
		$input.parent().addClass('focus');
	});
	$input.keydown(function(e){
		if(e.which == 13){
			e.preventDefault();
			search();
		}
		if($input.text() == searchPlaceholderText){
			$input.parent().removeClass('interacted');
			$input.text('');
		}else{
			$input.parent().addClass('interacted').removeClass('autofilled');
			$input.blur(function(){
				$input.parent().removeClass('focus');
				$input.focus(function(){
					$input.selectText();
				});
			});
		}
	});
	$input.keypress(function(e){
		if(e.which != 13){ //ignore enter key so that search code runs
			var key = String.fromCharCode(e.which);
			if(key.match(/[^a-zA-Z .,!?'\d]/g)){ //only allow characters and common puncuation
				e.preventDefault();
			}
		}
	});
	$input.keyup(function(){
		if($input.text() == ''){
			$input.parent().removeClass('interacted');
			$input.text(searchPlaceholderText);
		}else{
			if($input.html().indexOf("<br>") != -1){
				var newText = $input.html().replace(/\<br\>/g, '');
				$input.text(newText);
				$input.focus();
				$input.setCaretPosition(newText.length);
			}
		}
	});
	//move caret to begging of input when placeholder text is in place
	$input.click(function(){
		if($input.text() == searchPlaceholderText){
		     setTimeout(function(){
				var sel, range;
				if(window.getSelection && document.createRange){
					range = document.createRange();
					range.selectNodeContents($input[0]);
					range.collapse(true);
					sel = window.getSelection();
					sel.removeAllRanges();
					sel.addRange(range);
		        }else if (document.body.createTextRange){
					range = document.body.createTextRange();
					range.moveToElementText($input);
					range.collapse(true);
					range.select();
		        }
			}, 1);
		}
	});
	$(".search-btn").click(function(){
		search();
	});
	$(".random-btn").click(function(){
		var quote = season_10_quotes[Math.floor(Math.random()*season_10_quotes.length)];
		$input.parent().removeClass('focus').addClass('interacted');
		$input.text(quote);
		search();
	});

	var fuse = new Fuse(subtitles, { keys: ['text'], maxPatternLength:255, threshold:0.3 });
	function search(){
		var query = $input.text();
		var query_str = encodeURIComponent(query).replace(/\%20/g, '+');
		history.pushState(null, null, '/search/' + query_str);
		Cookies.remove('last-query');
		Cookies.set('last-query', query_str);
		$input.parent().addClass('autofilled');
		var $results = $(".search-results");
		$results.addClass('active loading');
		$results.removeClass('none-found');
		$(".hero-wrapper").addClass('searching');
		var $results_container = $(".search-results .results"); 
		$results_container.empty();

		var search_result = fuse.search(query);
		//console.debug(search_result);
		$results.removeClass('loading');
		if(search_result.length > 0){
			search_result = search_result.slice(0, 30); //limit to 30 quotes
			search_result.forEach(function(line){
				var html = "<a class='quote-wrapper' href='/edit/" + line.season + "/" + line.episode + "/" + line.start + "-" + line.stop +"'>";
						html += "<div class='quote'>";
							html += "<img src='/thumbnail/" + line.season + "/" + line.episode + "/" + line.start + "-" + line.stop + "'>";
							html += "<div class='text-background'></div>";
							html += "<div class='line'>" + line.text + "</div>";
						html += "</div>";
					html += "</a>";
				//console.log(html);
				$results_container.append(html);
			});
		}else{
			$results.addClass('none-found');
		}
	}

	$input.focus();
});