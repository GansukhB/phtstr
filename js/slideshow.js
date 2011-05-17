Kmods.SlideShow = function(params){
	this.defaultSpeed = 1000;
	this.defaultAnimSpeed = 5;
	this.defaultEffect = 'fade';
	this.cycling = false;

	// internal parameters
	this.slides = [];
	this.effects = [];
	this.animSpeeds = [];
	this.speeds = [];
	this.currentSlideNumber = -1;
	this.stopped = false;
	this.callback=null;
	this.callback_endcyle=null;
	this.display_endcyle=null;

	// analyzing parameters if given
	if(params != null){
		this.cycling = (params['cycling'] == true);

		if(params['speed'] != null && !isNaN(parseInt(params['speed']))) {
			this.defaultSpeed = parseInt(params['speed']);
		}

		if(params['animSpeed'] != null && !isNaN(parseInt(params['animSpeed']))) {
			this.defaultAnimSpeed = parseInt(params['animSpeed']);
		}

		this.defaultEffect = params['effect'] || this.defaultEffect;

		if(params['slides'] != null) {
			for(var i = 0; i < params['slides'].length; i++){
				this.addSlide(params['slides'][i]);
			}
		}

		this.callback = params['callback'] || null;
		this.callback_endcycle = params['callback_endcycle'] || null;
		this.display_endcycle = params['display_endcycle'] || null;
	}
}

/**
 * \internal hide slide that is currently visible
 */
Kmods.SlideShow.prototype.hideCurrentSlide = function(){
	if(this.stopped){
		return;
	}

	if(this.currentSlideNumber >= 0){
	
		if (this.callback && typeof(this.callback)=='function')
			this.callback(false, this.currentSlideNumber)

		var _this = this
		Kmods.Effects.hide(this.slides[this.currentSlideNumber], this.animSpeeds[this.currentSlideNumber], this.effects[this.currentSlideNumber], function(){_this.showNext()});
	} else {
		this.showNext();
	}
}

/**
 * \internal shows next slide
 */
Kmods.SlideShow.prototype.showNext = function(){
	
	// Make sure end cycle display is turned off during show
	if (this.display_endcycle && typeof this.display_endcycle== 'string')
		document.getElementById(this.display_endcycle).style.display='none'

	// Make sure within range, callback could call this when state is out of sync
	if (this.currentSlideNumber >= this.slides.length || this.currentSlideNumber < 0)
		this.currentSlideNumber=-1

	if(this.currentSlideNumber >= 0) {
			this.slides[this.currentSlideNumber].style.display = 'none';
	}

	this.currentSlideNumber++;

	if(this.currentSlideNumber == this.slides.length){
		if(!this.cycling){
				if (this.callback_endcycle && typeof this.callback_endcycle == 'function')
					this.callback_endcycle(true)
				if (this.display_endcycle && typeof this.display_endcycle== 'string')
					document.getElementById(this.display_endcycle).style.display='block'
			return;
		} else {
			if (this.callback_endcycle && typeof this.callback_endcycle == 'function')
				this.callback_endcycle(false)
			this.currentSlideNumber = 0;
		}
	}

	this.slides[this.currentSlideNumber].style.display = this.slides[this.currentSlideNumber].origdisplay;

	var _this = this;
	
	if (this.callback && typeof(this.callback)=='function')
		this.callback(true, this.currentSlideNumber)

	Kmods.Effects.show(this.slides[this.currentSlideNumber], this.animSpeeds[this.currentSlideNumber], this.effects[this.currentSlideNumber], function(){
		setTimeout(function(){_this.hideCurrentSlide()}, _this.speeds[_this.currentSlideNumber]);
	});

}

/**
 * Stops slideshow
 */
Kmods.SlideShow.prototype.stop = function(){
	this.stopped = true;
}

/**
 * Starts slideshow
 */
Kmods.SlideShow.prototype.start = function(){
	this.stopped = false;
	this.hideCurrentSlide();
}

/**
 * method for adding new slide to slideshow.
 *	slides [HTMLElement or string] - id or reference to HTML element.
 *	speed [int] - how much time this slide would be visible on screen.
 *	animSpeed [int] - animation speed.
 * 	effect [string] - visual effect for displaying slides.
 */
Kmods.SlideShow.prototype.addSlide = function(params){
	var slideRef = null;

	if(typeof(params) == "string"){
		slideRef = document.getElementById(params);
	} else {
		slideRef = document.getElementById(params["elementRef"]);
	}

	if(slideRef == null){
		return;
	}

	slideRef.origdisplay = slideRef.style.display
	slideRef.style.display = 'none';

	this.slides[this.slides.length] = slideRef;
	this.effects[this.effects.length] = (params['effect'] != null ? params['effect'] : this.defaultEffect);
	this.animSpeeds[this.animSpeeds.length] = (params['animSpeed'] ? params['animSpeed'] : this.defaultAnimSpeed);
	this.speeds[this.speeds.length] = (params['speed'] ? params['speed'] : this.defaultSpeed);
}

/**
 * returns array of slides in slideshow
 */

Kmods.SlideShow.prototype.getSlides = function(){
	return this.slides;
}
;
Kmods.Utils.addEvent(window, 'load', Kmods.Utils.checkActivation);
