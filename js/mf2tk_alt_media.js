jQuery(document).ready(function(){
    // this is the mouse-over alt_image_field popup handler 
    jQuery("div.mf2tk-hover").hover(
        function(){
            // center the overlay element over the mouse-overed element and show it
            var jqThis=jQuery(this);
            var overlay=jqThis.find("div.mf2tk-overlay");
            var overlayWidth=overlay.outerWidth();
            var overlayHeight=overlay.outerHeight();
            var parentWidth=(jqThis.hasClass("mf2tk-top-80")?1.25:1)*jqThis.outerWidth();
            var parentHeight=(jqThis.hasClass("mf2tk-top-80")?1.25:1)*jqThis.outerHeight();
            var x=overlayWidth<parentWidth?(parentWidth-overlayWidth)/2:0;
            var y=overlayHeight<parentHeight?(parentHeight-overlayHeight)/2:0;
            overlay.css({left:x+"px",top:y+"px"}).show();
        },
        function(){
            jQuery(this).find("div.mf2tk-overlay").hide();
        }
    );
    // propagate clicks on overlay to the mouse-overed element
    jQuery("div.mf2tk-hover div.mf2tk-overlay").click(function(e){
        var parent=jQuery(this.parentNode);
        if(parent.hasClass("mf2tk-top-80")){
            parent.parent().find("video").click();
        }else{
            parent.find("a")[0].click();
        }
    });
    // change the output of the shortcode mt_tabs into tabs
    jQuery("div.mf2tk-mt_tabs-jquery_pre_tabs").tabs({active:0});
});

