function initialize() {
	
}

$(document).ready(function() {
	$("#gallery ul").cycle({pause: true});

	$("#gallery img").hover(function() {
		$(this).next().slideToggle();
	});

	$("#videos ul").jcarousel({
    	wrap: 'circular',
    	buttonNextHTML: '<div class="carousel-nav" id="carousel-next" title="Next"><span>&gt</span></div>',
    	buttonPrevHTML: '<div class="carousel-nav" id="carousel-prev" title="Previous"><span>&lt<span></div>'
    });

    if($("#youtube").length>0) {
        hw = ($("#videos.large").length>0) ? [688,532] : [455,352];
        $("#youtube").tubeplayer({
            initialVideo: $("#youtube").attr("rel"),
            height: hw[1],
            width: hw[0],
            showinfo: true,
            onPlay: function() {
                //console.log(this);
            }
        });

        $("#videos li a").click(function() {
            t = $(this);
            tt = t.find("span").text();
            l = t.attr("rel");
            y = $("#youtube");
            yl = y.attr("rel");
            yt = y.attr("title");
            //
            y
                .tubeplayer("play", l)
                .attr({
                    rel: l,
                    title: tt
                });

            t
                .attr("rel",yl)
                .find("span").text(yt).parent()
                .next()
                    .attr("src","http://img.youtube.com/vi/" + yl + "/3.jpg");
            return false;
        });

        
    }

    //change calendar labels
    $("label.ai1ec-label").html("Filter by region:");
});