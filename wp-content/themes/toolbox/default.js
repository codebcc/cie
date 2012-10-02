function initialize() {
	
}

$(document).ready(function() {

	$("#videos ul").jcarousel({
    	wrap: 'circular',
    	buttonNextHTML: '<div class="carousel-nav" id="carousel-next" title="Next"><span>&gt</span></div>',
    	buttonPrevHTML: '<div class="carousel-nav" id="carousel-prev" title="Previous"><span>&lt<span></div>'
    });

    if($("body.home #gallery").length>0) {
        $("#gallery ul").cycle({pause: true});

        var height = 0;

        $("#gallery img").hover(function() {
            $(this).next().slideToggle();
        }).each(function() {
            t = $(this);
            if(t.height()>height) height = t.height();
        });
        $("#gallery").height(height);

    }

    if($("#youtube").length>0) {
        hw = ($("#videos.large").length>0) ? [688,532] : [455,352];
        if($("#youtube").attr("audio").length<1) {
            
            $("#youtube").tubeplayer({
                initialVideo: $("#youtube").attr("rel"),
                height: hw[1],
                width: hw[0],
                showinfo: true,
                onPlay: function() {
                    //console.log(this);
                }
            });

        } else {

        }

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