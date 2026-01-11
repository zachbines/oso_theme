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

// Elements
const elements = {  
  header: document.getElementById('masthead'),
  headerVideo1: document.getElementById('headerVideo1'),
  headerVideo2: document.getElementById('headerVideo2'),
  headerLogoImage: document.getElementById('headerLogoImage'),
  nav: document.getElementById('site-navigation'),
  mainContent: document.querySelector('.site-main'),
  body: document.querySelector('body')
};

// State
let activeVideo = 1;

// Start header videos
function startHeaderVideos() {
  elements.headerVideo1.currentTime = 0;
  elements.headerVideo2.currentTime = 0;
  elements.headerVideo1.play().catch(console.log);
}

// Start crossfade
function startCrossfade() {
  setInterval(() => {
    const current = activeVideo === 1 ? elements.headerVideo1 : elements.headerVideo2;
    const next = activeVideo === 1 ? elements.headerVideo2 : elements.headerVideo1;

    if (current.duration && current.currentTime >= current.duration - 6) {
      // Start next video and add active class immediately
      next.currentTime = 0;
      next.play().catch(console.log);
      next.classList.add('active');

      // Remove active from current after a small delay
      // This ensures both videos overlap during the fade
      setTimeout(() => {
        current.classList.remove('active');
      }, 100);

      activeVideo = activeVideo === 1 ? 2 : 1;
    }
  }, 100);

  elements.body.classList.add('allow-scroll');
}

// Initialize scroll effects
function initScrollEffects() {
  const scrollElements = document.querySelectorAll('.scroll-fade');
  const headerHeight = 180; // Match header height

  const handleScroll = () => {
    scrollElements.forEach(element => {
      const rect = element.getBoundingClientRect();
      const elementTop = rect.top;
      const elementHeight = rect.height;

      // Check if element is passing behind header or already past it
      if (elementTop < headerHeight) {
        // Element is at or past the header threshold
        const fadeProgress = Math.max(0, Math.min(1, (headerHeight - elementTop) / headerHeight));

        if (fadeProgress > 0.1) {
          element.classList.add('fading');
          // Apply progressive fade based on scroll position
          element.style.opacity = Math.max(0.2, 1 - fadeProgress);
          element.style.transform = `translateY(${-fadeProgress * 30}px) scale(${1 - fadeProgress * 0.1})`;
          element.style.filter = `blur(${fadeProgress * 4}px)`;
        }
      } else {
        // Element hasn't reached header yet - keep it normal
        element.classList.remove('fading');
        element.style.opacity = '';
        element.style.transform = '';
        element.style.filter = '';
      }
    });
  };

  // Add scroll listener with throttling for performance
  let ticking = false;
  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        handleScroll();
        ticking = false;
      });
      ticking = true;
    }
  });

  // Initialize portfolio video interactions
  initPortfolioVideos();
}

// Initialize portfolio videos
function initPortfolioVideos() {
  const portfolioItems = document.querySelectorAll('.portfolio-item');
  const modal = document.getElementById('videoModal');
  const modalVideo = document.getElementById('modalVideo');
  const modalVideoSource = document.getElementById('modalVideoSource');
  const modalTitle = document.getElementById('modalTitle');
  const closeModal = document.getElementById('closeModal');
  const fullscreenBtn = document.getElementById('fullscreenBtn');

  portfolioItems.forEach(item => {
    const video = item.querySelector('video');
    const videoSrc = item.dataset.video;
    const title = item.dataset.title;

    // set background to poster image
    if (video.poster) {
      item.style.backgroundImage = `url('${video.poster}')`;
    }

    // Hover to play preview
    item.addEventListener('mouseenter', () => {
      video.classList.add('active');
      video.play().catch(console.log);
    });

    item.addEventListener('mouseleave', () => {
      video.classList.remove('active');
      video.pause();
      video.currentTime = 0;
    });

    // Click to open modal
    item.addEventListener('click', () => {
      modalVideoSource.src = videoSrc;
      modalTitle.textContent = title;
      modalVideo.load();
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
  });

  // Close modal
  closeModal.addEventListener('click', () => {
    modal.classList.remove('active');
    modalVideo.pause();
    document.body.style.overflow = '';
  });

  // Close on background click
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('active');
      modalVideo.pause();
      document.body.style.overflow = '';
    }
  });

  // Fullscreen functionality
  fullscreenBtn.addEventListener('click', () => {
    if (modalVideo.requestFullscreen) {
      modalVideo.requestFullscreen();
    } else if (modalVideo.webkitRequestFullscreen) {
      modalVideo.webkitRequestFullscreen();
    } else if (modalVideo.mozRequestFullScreen) {
      modalVideo.mozRequestFullScreen();
    } else if (modalVideo.msRequestFullscreen) {
      modalVideo.msRequestFullscreen();
    }
  });

  // ESC key to close modal
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
      modal.classList.remove('active');
      modalVideo.pause();
      document.body.style.overflow = '';
    }
  });
}

// Initialize everything
function init() {
  console.log('init has run');

  startHeaderVideos();

  elements.headerLogoImage.classList.add('visible');
  elements.nav.classList.add('visible');

  setTimeout(() => {
    elements.header.classList.add('visible');
    elements.mainContent.classList.add('visible');
    elements.headerVideo1.classList.add('active');

    startCrossfade();
    initScrollEffects();
  }, 500);
}

// Start on DOM ready
document.addEventListener('DOMContentLoaded', init);