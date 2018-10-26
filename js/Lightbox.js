function Lightbox(oConfig){
	
	if(!oConfig){
		oConfig = {};
	}
	
	// container that holds the images to be loaded in a lightbox
	this.container = oConfig.container || '.lightbox';
	
	// selectors
	this.lightboxContainerSelector = '.lightbox-container';
	this.overlaySelector = this.lightboxContainerSelector + ' .lightbox-overlay';
	this.leftArrowSelector = this.lightboxContainerSelector + ' .lightbox-left-arrow';
	this.rightArrowSelector = this.lightboxContainerSelector + ' .lightbox-right-arrow';
	this.imgSelector = this.lightboxContainerSelector + ' img';
	
	// images
	this.imageList = [];
	this.imageListIdx = 0;
	this.image = '';
	
	this.init = function(){
		var self = this;
		this.imageList = $(this.container).find('img');
		this.imageList.each(function(idx, el){
			$(el).click(function(){
				self.imageListIdx = idx;
				self.handleClick();
			});
			$(el).css({
				cursor: 'pointer'
			});
		}.bind(this));
	};
	
	this.handleClick = function(e){
		console.log('handleClick', arguments);
		var el = this.imageList[this.imageListIdx];
		this.image = $(el).attr('src');
		this.appendLightbox();
	};

	this.appendLightbox = function(){
		var lightboxContainer = "<div class='lightbox-container'></div>";
		$('body').append(lightboxContainer);
		this.appendOverlay();
	};
	
	this.appendOverlay = function(){
		var overlay = "<div class='lightbox-overlay'></div>";
		$(this.lightboxContainerSelector).append(overlay);
		$(this.overlaySelector).click(this.removeOverlay.bind(this));
		
		this.appendLeftArrow();
		this.appendRightArrow();
		this.appendImage();
		this.setupArrowClicks();
		this.setupKeyboardHandlers();
	};
	
	this.appendLeftArrow = function(){
		var arrow = "<div class='lightbox-left-arrow'><span class='fa fa-4x fa-chevron-left'></span></div>"
		$(this.lightboxContainerSelector).append(arrow);
	};
	
	this.appendRightArrow = function(){
		var arrow = "<div class='lightbox-right-arrow'><span class='fa fa-4x fa-chevron-right'></span></div>"
		$(this.lightboxContainerSelector).append(arrow);
	};
	
	this.appendImage = function(){
		var img = "<img src='" + this.image + "'>";
		$(this.lightboxContainerSelector).append(img);
		
		setTimeout(function(){
			var windowWidth = window.innerWidth;
			var leftArrowWidth = $(this.leftArrowSelector).width();
			var rightArrowWidth = $(this.rightArrowSelector).width();
			var maxWidth = windowWidth - leftArrowWidth - rightArrowWidth - 80; // 40 = 20px movement from edge of arrows
			$(this.lightboxContainerSelector).find('img').css({
				maxWidth: maxWidth,
				width: maxWidth
			});
		}.bind(this),1);
		
	};
	
	this.setupArrowClicks = function(){
		var self = this;
		
		$(this.leftArrowSelector).click(function(){
			if(self.imageListIdx > 0){
				self.imageListIdx--;
				self.renderCurrentImage();
			}
		});
		
		$(this.rightArrowSelector).click(function(){
			if(self.imageListIdx < self.imageList.length-1){
				self.imageListIdx++;
				self.renderCurrentImage();
			}
		});
		
	};
	
	this.setupKeyboardHandlers = function(){
		var self = this;
		window.addEventListener('keyup', self.keyboardHandler, false);
	};
	
	this.keyboardHandler = function(e){
		console.log('keyboardHandler');
		var self = this;
		if(e.keyCode === 37){
			$(self.leftArrowSelector).trigger('click');
		}
		if(e.keyCode === 39){
			$(self.rightArrowSelector).trigger('click');
		}
	};
	
	this.renderCurrentImage = function(){
		var el = $( this.imageList[this.imageListIdx]) ;
		$(this.imgSelector).attr('src', el.attr('src'));
		$('html, body').animate({
			scrollTop: el.offset().top
		});
	};
	
	this.removeOverlay = function(){
		window.removeEventListener('keyup', this.keyboardHandler, false);
		$(this.lightboxContainerSelector).remove();
	};
	
}

/*

<div class='lightbox-container'>
	<div class='lightbox-overlay'>
	</div>
	<div class='lightbox-guts'>
		<div class='lightbox-left'></div>
		<div class='lightbox-right'></div>
		<img src='' class='lightbox-image'>
	</div>
</div>


 */