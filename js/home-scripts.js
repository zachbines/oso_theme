( function() {
    // console.log('hey!!',window.token);
    let xhr = new XMLHttpRequest();
    xhr.open('GET','https://api.vimeo.com/users/'+window.vimeo_uid+'/videos?per_page=9&sort=date&filter=featured')
    xhr.setRequestHeader('Authorization', 'bearer '+window.token);
    xhr.send();
    xhr.onload = function(){
        // console.log('xhr onload',xhr.status);
        if (xhr.status=='200'){
            var res = JSON.parse(xhr.response);
            // console.log(res.data);
            renderResults(res.data);
        }
    }

    if (document.getElementById('v_feature')){
        console.log('on home page');
        window.herovid = new Vimeo.Player('v_feature');
        window.herovid.on('play', function(e){ handleVideoEvent('play',e) });
        window.herovid.on('pause', function(e){ handleVideoEvent('pause',e) });
        window.herovid.on('volumechange', function(e){ handleVideoEvent('volume',e) });

        jQuery('#v_playpause').on('click',togglePlayPause);
        jQuery('#v_volume').on('click',toggleVolume);
    }
    if (document.getElementById('feature-grid')) {
        const list = document.getElementById('feature-grid').getAttribute('data-vlist').split(',');
        console.log(list);
        for (var i=0;i<list.length;i++){
            queryFeatureVideo(list[i]);
        }
    }
}() );

function queryFeatureVideo(id){
    let query = 'https://api.vimeo.com/videos/'+id;

    let xhr = new XMLHttpRequest();
    xhr.open('GET',query)
    xhr.setRequestHeader('Authorization', 'bearer '+window.token);
    xhr.send();
    xhr.onload = function(){
        // console.log('xhr onload',xhr.status);
        if (xhr.status=='200'){
            var res = JSON.parse(xhr.response);
            console.log(res);
            renderFeatureVideo(res);
        }
    }
}
function renderFeatureVideo(res){
    var vview = new videoView( {model:res} );
    vview.render();
    vview.$el.on('click',handleVideoClick);
    jQuery(".featured_grid").append(vview.$el);
}

function renderResults(res,total){
    console.log(res);

    jQuery(".video_grid").empty();
    

    res.forEach(function(elem){
        // console.log('render',elem);
        var vview = new videoView( {model:elem} );
        vview.render();
        vview.$el.on('click',handleVideoClick);
        jQuery(".video_grid").append(vview.$el);
    });
    
}

function handleVideoClick(e){
    var v_thumb = jQuery(e.target);
    var v_id = v_thumb.first('div').data('vid');
    // console.log(v_thumb,v_id);
    this.blur(); // Manually remove focus from clicked link.

    if (window.herovid) window.herovid.pause();

    var mview = new modalVideoView( {model:{v_id:v_id}} );
    mview.render();
    jQuery(mview.$el).appendTo('body').modal();
    
    var v_modal = mview.$el.find('#v_modal');

    var mplayer = new Vimeo.Player(v_modal,{ });
    mplayer.on('play',function(){
        // console.log('playing!');
    });

    jQuery(mview.$el).on(jQuery.modal.BEFORE_CLOSE, function(){
        // console.log('pause modal video');
        //mplayer.pause();
        mview.$el.empty(); // destroy modal video
    });
}

function togglePlayPause(){
    window.herovid.getPaused().then(function(isPaused){
        if (isPaused){
            window.herovid.play();
        } else {
            window.herovid.pause();
        }
    })
}
function toggleVolume(){
    window.herovid.getVolume().then(function(volume){
        // console.log('vol',volume);
        if (volume==0){
            window.herovid.setVolume(1);
        } else {
            window.herovid.setVolume(0);
        }
    })
}
function handleVideoEvent(e,obj){
    if (e=="play"){
        jQuery('#v_playpause').toggleClass('active',true);
    } else if (e=="pause") {
        jQuery('#v_playpause').toggleClass('active',false);
    } else {
        jQuery('#v_volume').toggleClass('active',obj.volume==1);
    }
}

var videoView = Backbone.View.extend({
    template : _.template(jQuery("#video_template").html()),
    className : "single_video",
    initialize : function() {
    },
    render : function() {
        this.$el.html(this.template( this.model ));
        return this;
    }
});
var modalVideoView = Backbone.View.extend({
    template : _.template(jQuery("#single_video_template").html()),
    className : "modal_video",
    initialize : function() {
    },
    render : function() {
        this.$el.html(this.template( this.model ));
        return this;
    }
});