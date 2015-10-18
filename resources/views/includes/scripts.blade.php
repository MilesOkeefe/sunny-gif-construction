<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
<script>
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
	});
</script>
<script src="/materialize-css/js/materialize.min.js"></script>
<!--<script src="//fb.me/react-with-addons-0.13.3.js"></script>
<script src="/js/react.min.js"></script>-->