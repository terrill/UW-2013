jQuery(document).ready(function(e){var t;e("body").on("click",".select-an-image",function(n){n.preventDefault();var r=e(this),i=r.siblings(".image-preview"),s=r.siblings("input");if(t){t.open();return}t=wp.media.frames.frame=wp.media({className:"media-frame single-image-media-frame",frame:"select",multiple:!1,title:"Select an image"});t.on("select",function(){var n=t.state().get("selection").first().toJSON(),r=e("<img/>").attr({src:n.url,width:"100%"});console.log("here");i.html(r);s.first().val(n.id).end().last().val(n.url)});t.open()})});jQuery.noConflict()(window).load(function(){if(typeof panelsData=="undefined")return;var e=jQuery(".site-panels-image-fix");e.each(function(){var e=jQuery(this),t=jQuery("<img/>").attr({src:e.val(),width:"100%"});e.siblings(".image-preview").html(t)})});