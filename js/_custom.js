{
	$('.search').on("change keyup paste", function () {
		if ($(this).val() == "")
			$('.search-content ul').html('');
		else {
			$.ajax({
				url: '/search.php',
				type: 'POST',
				data: {name: $(this).val()},
				success: function(msg){
					msg = JSON.parse(msg);
					if (!msg.error) {
						$('.search-content ul').html('');
						for (var i = 0; i < msg.content.length; i++) {
							$('.search-content ul').append('<li><a href="/product.php?id='+msg.content[i].id+'"><span>'+msg.content[i].name+'</span> <span>'+msg.content[i].cost+'</span>($)</a></li>')
						}
					}				
				}
			})
		}
	});
}