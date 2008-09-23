var assessmentGlider;
var slider;
var totalQuestions;
var currentSlide = 0;

Event.observe(window, 'load', function() {
	$('assessment-previous-button').hide();
	Event.observe('assessment', 'submit', checkAnswers);
	
	assessmentGlider = new Glider('assessment-glider', {duration:0.5});
	var answeredQuestions = new Array();

	createSlider();
	setTotalQuestions();
	
	$('assessment-previous-button').hide();
});

function updateSlider(value) {

	value = slider.getNearestValue(value);
	if(value > currentSlide) {
		currentSlide++;
	} else if (value < currentSlide) {
		currentSlide--;
	}
	$("current_question").update(currentSlide+1);
	questions = $('assessment-glider').getElementsBySelector('.question');
	assessmentGlider.moveTo(questions[currentSlide], $('scroller'), { duration: 0.5});
}

function checkAnswers(e) {
	var allAnswered = true;
	var questions = $('assessment').getElementsBySelector('.question');

	questions.each(function (question) {
		if(!hasAnswer(question)) {
			allAnswered = false;
			
			// Abusing Effect.Highlight to fade the color in but not back out.  Effect.Tween would be a better long term option.
			new Effect.Highlight(question, {startcolor: '#ffff99', endcolor: '#ffff99', restorecolor: '#ffff99'});
			
			assessmentGlider.moveTo(question, $('scroller'), { duration: 0.5});
			$("current_question").update(question._index+1);
			currentSlide = question._index;
			slider.setValue(currentSlide);
			if(currentSlide == 0) {
				$('assessment-previous-button').hide();
				$('assessment-next-button').show();
			} else if (currentSlide == totalQuestions-1){
				$('assessment-next-button').show();
				$('assessment-previous-button').hide();
			} else {
				$('assessment-next-button').show();
				$('assessment-previous-button').show();
			}
			throw($break);
		}
	});
	
	if(!allAnswered) {
		Event.stop(e);
	}
}

function hasAnswer(question) {
	if(question.getElementsBySelector('input[type="radio"]').find(function(radio) { return radio.checked;} )) {
		return true;
	} else {
		return false;
	}
}

function updatePercentComplete() {
	totalQuestions = 3;
	widthPerQuestion = $('progress-container').getWidth() / totalQuestions;
	currentWidth = $('progress-bar').getWidth();
	
	alert(widthPerQuestion);
	alert(currentWidth);
	
	$('progress-bar').setStyle({
		width: currentWidth + widthPerQuestion + 'px'
	});
}

function onQuestionAnswer (event) {
	elt = Event.element(event);
	parent = elt.up();
	if (hasAnswer(parent)) {
		alert('already has an answer');
	} else {
		alert('no answer');
	}
	updatePercentComplete();
}

function previousQuestion() {
	if(currentSlide != 0) {
		currentSlide--;
	}
	
	if(currentSlide == 0) {
		$('assessment-previous-button').hide();
	} else {
		$('assessment-next-button').show();
	}
	
	assessmentGlider.previous();
	slider.setValue(currentSlide);
	$("current_question").update(currentSlide+1);
		
	return false;
}

function nextQuestion() {
	if(currentSlide != totalQuestions-1) {
		currentSlide++;
	}
	
	if(currentSlide == totalQuestions-1) {
		$('assessment-next-button').hide();
	} else {
		$('assessment-previous-button').show();
	}
	
	if(currentSlide != totalQuestions) {
		assessmentGlider.next();
		slider.setValue(currentSlide);
		$("current_question").update(currentSlide+1);
	}

	return false;
}