
var voteForm = '<div class="vote-background">'+
'		<div class="vote-background-best"><div class="vote-bgtext">Legjobb</div></div>'+
'		<div class="vote-background-good"><div class="vote-bgtext">Elfogadható</div></div>'+
'		<div class="vote-background-none"><div class="vote-dummy"><div class="vote-choicetext">A lentieket ellenzem:</div></div></div>'+
'		<div class="vote-background-bad"><div class="vote-bgtext">Elfogadhatatlan</div></div>'+
'		<div class="vote-background-badest"><div class="vote-bgtext">De gonosz! Totál elvetemült! És citromillatú!</div></div>'+
'	</div>'+
'	<div class="vote-form">'+
'		<div id="vote-alternatives-{0}" class="vote-alternatives">'+
'		</div>'+
'		<div class="vote-sliderbox" id="vote-sliderbox-{0}">'+
'		</div>'+
'	</div>';

if (!String.prototype.format) {
  String.prototype.format = function() {
	var args = arguments;
	return this.replace(/{(\d+)}/g, function(match, number) { 
	  return typeof args[number] != 'undefined'
		? args[number]
		: match
	  ;
	});
  };
}

function VoteSlider(id, choices) {

	self = this;
   	function update(choiceDiv, slider) {
	   return function () {
			setPosition(choiceDiv, slider.value);
	   }
  	}

	function dragMove(event) {
		var pos = event.clientY - self.y;
		self.elem.style.top = pos + 'px';
		var sliderValue = (- pos + self.allHeight - self.alternativeHeight)/self.multiplier ;
		self.slider.value = sliderValue;
	}
	function dragEnd(event) {
		self.parent.removeEventListener("mousemove", dragMove);	
		self.parent.removeEventListener("mouseup", dragEnd);	
	}
	function dragStart (inner,slider) {
		return function (event) {
			var x = event.clientX;
			var y = event.clientY;
			self.elem = inner;
			self.slider = slider;
			self.x = x-inner.offsetLeft;
			self.y = y-inner.offsetTop
			self.parent.addEventListener("mousemove", dragMove);
			self.parent.addEventListener("mouseup", dragEnd);
		}
	}

	function setPosition(e, value) {
		var pos = self.allHeight - value * self.multiplier - self.alternativeHeight;
		e.style.top = pos + 'px';
	}

	function addAlternative(slug) {
			var name = choices[slug]['name']
			var value = choices[slug]['value']
			if (value == undefined) {
				value = Math.random() * self.allHeight;
				console.log("randomized:" + value);
			}
			slider = document.createElement('input');
			slider.setAttribute('class', 'multirange original');
			slider.setAttribute('type', 'range');
			slider.setAttribute('orient', 'vertical');
			slider.setAttribute('max', self.allHeight);
			slider.setAttribute('min', 0);
			slider.setAttribute('value', value);
			self.sliderParent.appendChild(slider);
			choiceDiv = document.createElement('div');
			choiceDiv.innerHTML = '<div class="vote-choicetext">{0}</div>'.format(name);
			choiceDiv.classList.add("vote-alternative");
			choiceDiv.id = 'alternative-' + slug;
			setPosition(choiceDiv, value);
			self.alternativesParent.appendChild(choiceDiv);
			slider.addEventListener("input", update(choiceDiv,slider));
			choiceDiv.addEventListener("mousedown", dragStart(choiceDiv,slider));
	}
	function initDom() {
		parent = document.getElementById(id);
		self.parent = parent;
		parent.classList.add("vote-container");
		parent.innerHTML = voteForm.format(id);
		self.allHeight = parent.clientHeight;
		self.alternativeHeight = 40;//in css, multiple places
		self.multiplier = (self.allHeight - self.alternativeHeight)/self.allHeight;
		console.log('height=' + self.allHeight);
		self.alternativesParent = document.getElementById('vote-alternatives-' + id);
		self.sliderParent = document.getElementById('vote-sliderbox-' + id);
		for(slug in choices) {
			addAlternative(slug);
		}
	}

	if (document.readyState == "loading") {
		document.addEventListener("DOMContentLoaded", initDom);
	} else {
		initDom();
	}
}

