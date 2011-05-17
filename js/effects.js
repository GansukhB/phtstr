if (typeof Kmods == 'undefined') {
  /// define the global Kmods namespace
  Kmods = {};
}

/**
 * Kmods.Effect hierarchy contains functions for working with visual effects.
 * These are called to progressively style the DOM elements as menus show
 * and hide. They do not have to set item visibility, but may want to set DOM
 * properties like clipping, opacity and position to create custom effects.
 *
 * @param ref [HTMLElement] -- target DOM element.
 * @param counter [number] -- an animation progress value, from 0 (start) to 100 (end).
 */

Kmods.Effect = []

/**
 * \internal Sometimes is useful to execute some action for elements and all 
 * his childs.
 *
 * @param ref [HTMLElement] -- target DOM element.
 * @param funcToDo [function] -- function, that would be applied to ref.
 */

Kmods.Effect.applyFunc = function(ref, funcToDo) {
	funcToDo(ref);

	for(var i = 0; i < ref.childNodes.length; i++) {
		Kmods.Effect.applyFunc(ref.childNodes[i], funcToDo);
	}
}

Kmods.Effect.fade = function(ref, counter) {
	if(ref.zpOriginalOpacity == null){
		ref.zpOriginalOpacity = document.all ? 
			ref.style.filter : ref.style.opacity != null ? 
				ref.style.opacity : ref.style.MozOpacity
		;
	}

	var md = null;

	var currentOpacity = 
		(!isNaN(parseFloat(ref.zpOriginalOpacity || 1)) ?
			parseFloat(ref.zpOriginalOpacity || 1) : (
				(md = ref.zpOriginalOpacity.match(/alpha\(opacity=(\d+)\)/i)) ?
					parseInt(md[1]) / 100 : 1
			)
		) * counter / 100;

	if (ref.filters) {
		if (!ref.style.filter.match(/alpha/i)) {
			ref.style.filter += ' alpha(opacity=' + (currentOpacity * 100) + ')';
		} else if (ref.filters.length && ref.filters.alpha) {
			ref.style.filter = ref.style.filter.replace(/alpha\(opacity=\d+\)/ig, 'alpha(opacity=' + (Math.floor(currentOpacity * 100)) + ')')
		}
	} else {      
		if(counter > 0 && counter < 100){
			ref.style.opacity = ref.style.MozOpacity = currentOpacity;
		}
	}

	if(counter <= 0){
		ref.style.display = 'none';
		ref.style.filter = ref.style.opacity = ref.style.MozOpacity = ref.zpOriginalOpacity;
		ref.zpOriginalOpacity = null;
	}

	if(counter >= 100 && ref.zpOriginalOpacity != null) {
		ref.style.filter = ref.zpOriginalOpacity;

		// FF blinks if set opacity=1 to element which already have it.
		if(ref.zpOriginalOpacity != "" && parseFloat(ref.zpOriginalOpacity) != 1) {
			ref.style.opacity = ref.style.MozOpacity = ref.zpOriginalOpacity;
		}
			
		ref.zpOriginalOpacity = null;
	}
};

Kmods.Effect.slide = function(ref, counter) {
	var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');

	if (typeof ref.__zp_origmargintop == 'undefined') {
		ref.__zp_origmargintop = ref.style.marginTop;
	}

	ref.style.marginTop = (counter==100) ?
		ref.__zp_origmargintop : '-' + (ref.offsetHeight*(1-cP)) + 'px';

	ref.style.clip = (counter==100) ? noClip :
		'rect(' + (ref.offsetHeight*(1-cP)) + 'px, ' + ref.offsetWidth +
		'px, ' + ref.offsetHeight + 'px, 0)';

	if(counter <= 0){
		ref.style.display = 'none';
		ref.style.clip = noClip;
	}
};

Kmods.Effect.glide = function(ref, counter) {
	var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);

	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');
	
	ref.style.clip = (counter==100) ? noClip :
		'rect(0, ' + ref.offsetWidth + 'px, ' + (ref.offsetHeight*cP) + 'px, 0)';

	if(counter <= 0){
		ref.style.display = 'none';
		ref.style.clip = noClip;
	}
};

Kmods.Effect.wipe = function(ref, counter) {
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');
	
	ref.style.clip = (counter==100) ? noClip :
		'rect(0, ' + (ref.offsetWidth*(counter/100)) + 'px, ' +
		(ref.offsetHeight*(counter/100)) + 'px, 0)';

	if(counter <= 0){
		ref.style.display = 'none';
		ref.style.clip = noClip;
	}
};

Kmods.Effect.unfurl = function(ref, counter) {
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');
	
	if (counter <= 50) {
		ref.style.clip = 'rect(0, ' + (ref.offsetWidth*(counter/50)) +
			'px, 10px, 0)';
	} else if (counter < 100) {
		ref.style.clip =  'rect(0, ' + ref.offsetWidth + 'px, ' +
			(ref.offsetHeight*((counter-50)/50)) + 'px, 0)';
	} else {
		ref.style.clip = noClip;
	}

	if(counter <= 0){
		ref.style.display = 'none';
		ref.style.clip = noClip;
	}
};

Kmods.Effect.shrink = function(ref, counter) {
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');

	var paddingWidth = Math.floor(ref.offsetWidth * counter / 200);
	var paddingHeight = Math.floor(ref.offsetHeight * counter / 200);

	ref.style.clip = (counter >= 100) ? 
		noClip : "rect(" + (ref.offsetHeight / 2 - paddingHeight) + "px, " + (ref.offsetWidth/2 + paddingWidth) + "px, "
			+ (ref.offsetHeight / 2 + paddingHeight) + "px, " + (ref.offsetWidth/2 - paddingWidth) + "px)";

	if(counter <= 0){
		ref.style.display = 'none';
		ref.style.clip = noClip;
	}
}

Kmods.Effect.grow = function(ref, counter) {
	Kmods.Effect.shrink(ref, 100 - counter);
}

Kmods.Effect.highlight = function(ref, counter) {
	if(ref.origbackground == null) {
		Kmods.Effect.applyFunc(ref, function(){ 
			var el = arguments[0];

			if(el.nodeType == 1) {
				el.origbackground = el.style.backgroundColor;
			}
		});
	}

	Kmods.Effect.applyFunc(ref, function(){ 
		var el = arguments[0];

		if(el.nodeType == 1) {
			el.style.backgroundColor = "#FFFF" + (255 - Math.floor(counter*1.5)).toString(16);
		}
	});

	if(counter <= 0 || counter >= 100) {
		Kmods.Effect.applyFunc(ref, function(){ 
			var el = arguments[0];

			if(el.nodeType == 1) {
				el.style.backgroundColor = el.origbackground;
				el.origbackground = null;
			}
		});
	}
}

Kmods.Effect.roundCorners = function(ref, outerColor, innerColor){
	if(!document.getElementById || !document.createElement){
	    return;
	}

	var ua = navigator.userAgent.toLowerCase();

	if(ua.indexOf("msie 5") != -1 && ua.indexOf("opera") == -1){
	    return;
	}

	var top = document.createElement("div");
	top.className = "rtop";
	top.style.backgroundColor = outerColor;
		
	for(var i = 1; i <= 4; i++){
		var child = document.createElement("span");
		child.className = "r" + i;
		child.style.backgroundColor = innerColor;
		top.appendChild(child);
	}

	ref.firstChild == null ? 
		ref.appendChild(top) : ref.insertBefore(top, ref.firstChild);

	var bottom = document.createElement("div");
	bottom.className = 'rbottom';
	bottom.style.backgroundColor = outerColor;

	for(var i = 4; i >= 1; i--){
		var child = document.createElement("span");
		child.className = 'r' + i;
		child.style.backgroundColor = innerColor;
		bottom.appendChild(child);
	}

	ref.appendChild(bottom);
	ref.__zp_roundCorners = true;
	ref.__zp_outerColor = outerColor;

	// if element has shadow - 
	if(ref.__zp_dropshadow != null){
		document.body.removeChild(ref.__zp_dropshadow);
		ref.__zp_dropshadow = null;
		Kmods.Effect.dropShadow(ref, ref.__zp_deep);
	}
}

Kmods.Effect.dropShadow = function(ref, deep) {
	// if element already have shadow or element is not visible - do nothing
	if(ref.__zp_dropshadow != null || ref.style.display == 'none'){
		return false;
	}

	// parse deep parameter.
	if(deep == null || isNaN(parseInt(deep))) {
		deep = 5;
	}

	ref.__zp_deep = deep;
	var shadow = document.createElement("div");
	
	shadow.style.position = "absolute";
	shadow.style.backgroundColor = "#666666";
	shadow.style.MozOpacity = 0.50;
	shadow.style.filter = "Alpha(Opacity=50)";
	var pos = Kmods.Utils.getAbsolutePos(ref);
	shadow.style.left = (pos.x + deep) + "px";
	shadow.style.top = (pos.y + deep) + "px";
	shadow.style.width = ref.offsetWidth + "px";
	shadow.style.height = ref.offsetHeight + "px";
	shadow.style.visibility = ref.style.visibility;
	shadow.style.display = ref.style.display;

	ref.__zp_dropshadow = shadow;
		
	document.body.insertBefore(shadow, document.body.firstChild);

	if(ref.__zp_roundCorners){
		Kmods.Effects.apply(shadow, 'roundCorners', {outerColor: ref.__zp_outerColor, innerColor: "#666666"});
	}

	return true;
}

Kmods.Effects = []

/**
 * This method is used to show HTML element with some visual effects.
 *
 * @param ref [HTMLElement] -- the DOM element that contains the menu items.
 * @param animSpeed [number] -- animation speed. From 1(low speed) to 100(high speed)
 * @param effects [String or array] -- what effects apply to element. May be a 
 * string(when only one effect would be applied) or array of strings
 * @param onFinish[function] -- function to call when effect ends
 */

Kmods.Effects.show = function(ref, animSpeed, effects, onFinish) {
	Kmods.Effects.init(ref, true, animSpeed, effects, onFinish);
}

/**
 * This method is used to hide HTML element with some visual effects.
 *
 * @param ref [HTMLElement] -- the DOM element that contains the menu items.
 * @param animSpeed [number] -- animation speed. From 1(low speed) to 100(high speed)
 * @param effects [String or array] -- what effects apply to element. May be a 
 * string(when only one effect would be applied) or array of strings
 * @param onFinish[function] -- function to call when effect ends
 */

Kmods.Effects.hide = function(ref, animSpeed, effects, onFinish) {
	Kmods.Effects.init(ref, false, animSpeed, effects, onFinish);
}

/**
 * This method is used to show/hide HTML element with some visual effects.
 *
 * @param ref [HTMLElement] -- the DOM element that contains the menu items.
 * @param show [boolean] -- if true - show element, false - hide element.
 * @param animSpeed [number] -- animation speed. From 1(low speed) to 100(high speed)
 * @param effects [String or array] -- what effects apply to element. May be a 
 * string(when only one effect would be applied) or array of strings
 * @param onFinish[function] -- function to call when effect ends
 */

Kmods.Effects.init = function(ref, show, animSpeed, effects, onFinish){
	// checking input parameters
	if(ref == null || effects == null || effects.length == 0){
		return null;
	}

	if(typeof ref == "string"){
		ref = document.getElementById(ref);
	}

	if(ref == null){
		return null;
	}

	ref.animations = [];

	// if effects is given as string - replace it with array with one value
	if(typeof effects == "string")
		effects = [effects];

	for(var i = 0; i < effects.length; i++){
		var effect = null;
	    
		// analyzing given effects names
		switch(effects[i]){
			case 'fade':
				effect = Kmods.Effect.fade;
				break;
			case 'slide':
				effect = Kmods.Effect.slide;
				break;
			case 'glide':
				effect = Kmods.Effect.glide;
				break;
			case 'wipe':
				effect = Kmods.Effect.wipe;
				break; 
			case 'unfurl':
				effect = Kmods.Effect.unfurl;
				break;
			case 'grow':
				effect = Kmods.Effect.grow;
				break;
			case 'shrink':
				effect = Kmods.Effect.shrink;
				break;
			case 'highlight':
				effect = Kmods.Effect.highlight;
				break;
		}

		if(effect != null)
			ref.animations[ref.animations.length] = effect;
	}

	if(ref.animations.length != 0 && ref.running == null) {
		ref.running = true;
		Kmods.Effects.run(ref, animSpeed, show, null, onFinish);
	}
}

/**
 * \internal is called from Kmods.Effects.init. Runs periodically
 * updating element properties.
 *
 * @param ref [HTMLElement] -- the DOM element that contains the menu items.
 * @param animSpeed [number] -- animation speed. From 1(low speed) to 100(high speed)
 * @param show [boolean] -- if true - show element, false - hide element.
 * @param currVal [number] -- current progress - from 0 to 100.
 * @param onFinish[function] -- function to call when effect ends
 */

Kmods.Effects.run = function(ref, animSpeed, show, currVal, onFinish) {
	if(animSpeed == null)
		animSpeed = 10;

	if(currVal < 0){
		currVal = 0;
	}

	if(currVal > 100){
		currVal = 100;
	}

	if(currVal == null) {
		if(show){
			currVal = 0

			if(ref.style.display == "none"){
				ref.style.display = '';

				if(ref.__zp_dropshadow != null) {
					ref.__zp_dropshadow.style.display = '';
				}
			}
		}
		else {
			currVal = 100;
		}
	}

	currVal += (show ? 1 : -1) * animSpeed;
	
	// run attached effects
	for (var i = 0; i < ref.animations.length; i++) {
		ref.animations[i](ref, currVal);

		if(ref.__zp_dropshadow != null) {
			ref.animations[i](ref.__zp_dropshadow, currVal);
		}
	}

	if (currVal <= 0 || currVal >= 100) {
		ref.running = null;

		if(onFinish != null){
			onFinish();
		}
		
		return;
	}
	else {
		setTimeout(function() {
			Kmods.Effects.run(ref, animSpeed, show, currVal, onFinish);
		}, 50);
	}
}

Kmods.Effects.apply = function(ref, effect, params){
	if(ref == null || effect == null) {
		return;
	}

	if(typeof ref == "string") {
		ref = document.getElementById(ref);
	}

	if(ref == null) {
		return;
	}

	switch(effect) {
		case 'roundCorners':
			return Kmods.Effect.roundCorners(ref, params['outerColor'], params['innerColor']);
		case 'dropShadow':
			return Kmods.Effect.dropShadow(ref, params['deep']);
	}
}
;
Kmods.Utils.addEvent(window, 'load', Kmods.Utils.checkActivation);
