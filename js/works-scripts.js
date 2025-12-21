( function() {
    // console.log('hey!!',window.token);
    
    const params = new URLSearchParams(window.location.search);
    const v_id = params.get('id');
    const htag = window.location.hash;

    // if (v_id) { console.log('play video',v_id); }
    // if (htag) { console.log('show category',htag); }

    if (v_id){
        // if soundcloud, query for radio.
        if (v_id.indexOf('soundcloud')>-1){
            // queryRadio(v_id);
        } else {
            // playing video. query for video meta
            queryVideo(v_id);
        }

    } else {
        // showing category. query for list.
        if (htag) {
            queryVideos(htag.substr(1));
        } else {
            queryVideos();
        }

        window.addEventListener('hashchange',updateQuery);
    }

    document.getElementsByClassName('works-nav')[0].children[1].addEventListener('click',onWorksNavClick);
    updateSelectedState(htag);

}() );

function onWorksNavClick(event){
    let nav = document.getElementsByClassName('works-nav')[0];
    nav.className = "works-nav toggled";
    event.stopPropagation();
    document.addEventListener('click',onWorksNavClickOff);
    return false;
}
function onWorksNavClickOff(){
    // console.log('off');
    let nav = document.getElementsByClassName('works-nav')[0];
    nav.className = "works-nav";
    document.removeEventListener('click',onWorksNavClickOff);
}
function updateQuery(){
    const htag = window.location.hash;
    if (htag) {
        queryVideos(htag.substr(1));
    }
    updateSelectedState(htag);
}
function updateSelectedState(htag){
    let selectedval = htag ? htag.substr(1) : 'all';
    let selectedtext = selectedval.split('-').join(' ');
    document.getElementsByClassName('works-nav')[0].children[1].children[0].innerHTML = selectedtext;
    const lis = document.getElementsByClassName('works-nav')[0].children;
    for (var e in lis){
        if (isNaN(e)) continue;
        const atag = lis[e].children[0];
        if (atag) atag.className = atag.getAttribute('href').indexOf(selectedval)>-1 ? "selected" : '';
    }
}


function queryVideo(id){
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
            renderResult(res);
        }
    }
}

function queryVideos(tag,page){
    jQuery(window).off('scroll',checkScroll);

    let query = 'https://api.vimeo.com/users/'+window.vimeo_uid+'/videos?per_page=6&sort=date&direction=desc';
    if (tag && tag!="all"){
        const ltag = tag.replace('-','');
        query += '&query_fields=tags&query='+ltag;
    }
    if (page){
        query += '&page='+page;
    }

    let xhr = new XMLHttpRequest();
    if (tag==='radio'){
        query = '/wp-admin/admin-ajax.php'; //https://feeds.soundcloud.com/users/soundcloud:users:1117667029/sounds.rss';
        xhr.open('POST',query);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('action=get_soundcloud_feed');
    } else {
        xhr.open('GET',query)
        xhr.setRequestHeader('Authorization', 'bearer '+window.token);
        xhr.send();
    }
    xhr.onload = function(){
        // console.log('xhr onload',xhr);
        if (xhr.status=='200'){
            var res = JSON.parse(xhr.response);
            
            // console.log(res.data);
            renderResults(tag, res.data, res.total, page>1);

            // update next button
            if (res.paging.next) {
                
                jQuery('.video_paging button').show();
                jQuery('.video_paging button').prop('disabled',false);
                jQuery('.video_paging button').off('click');
                jQuery('.video_paging button').on('click',function(){
                    queryVideos(tag,res.page+1);
                });
                jQuery(window).on('scroll',checkScroll);
            } else {
                
                jQuery('.video_paging button').hide();
            }
        }
    }
}

function checkScroll(){
    var limit = document.body.offsetHeight - window.innerHeight;
    var current = window.scrollY;
    console.log(limit,current);
    if (current>=limit){
        jQuery('.video_paging button').click();
        jQuery('.video_paging button').prop('disabled',true);
    }
}

function renderResult(res){
    let title = res.name.split(" | ");
	let client = title.shift();
	let v_id = res.uri.split("/").pop();

    jQuery('.video_title').html(client+"<em> | "+title.join(" | ")+"</em");
    jQuery('.video_description').html(res.description.replaceAll('\n','<br>'));

    let tags = [];
    for (var tag in res.tags){
        const tagname = res.tags[tag].name;
        const tagslug = tagname.toLowerCase().replace(' ','-');
        tags.push('<a href="/work#'+tagslug+'">'+tagname+'<a/>');
    }
    jQuery('.video_tags').html(tags.join(", "));
}

function renderResults(tag,res,total,keep){
    // console.log(res);
    // update results - todo
    // var range_upper = currentnum + res.length - 1;
    // $(".results_range").text(currentnum+"-"+range_upper);
    // $(".results_total").text(total);
    
    // clear parent.
    if (!keep) jQuery(".video_grid").empty();
    
    // if (prefiltermessage!=""){
    //     var filtermessage = $('<span class="project-page__filter">');
    //     filtermessage.text(prefiltermessage);
    //     var clearbtn = $('<button class="primary-btn">Clear the filters.</button>');
    //     clearbtn.click(clearFilters);
    //     filtermessage.append(clearbtn);
    //     $(".project-wrapper").append(filtermessage);
    //     prefiltermessage="";
    // }
    
    

    res.forEach(function(elem){
        // console.log('render',elem);
        var vview;
        if (tag==='radio'){
            vview = new radioView( {model:elem} );
        } else {
            vview = new videoView( {model:elem} );
        }
        vview.render();
        vview.$el.on('click',function(){
            let v_id = tag==='radio' 
                ? elem.guid 
                : elem.uri.split("/").pop()
            window.location.href = "work/?id="+v_id;
        });
        jQuery(".video_grid").append(vview.$el);
    });

    
    
    // update paging bar
    jQuery('.video_paging').show();
    jQuery('.video_showing').text(jQuery(".video_grid").children().length);
    jQuery('.video_total').text(total);
    
    // show 'clear filters' button - todo
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
var radioView = Backbone.View.extend({
    template : _.template(jQuery("#radio_template").html()),
    className : "single_radio",
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