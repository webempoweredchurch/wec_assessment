var assessmentGlider;
var slider;
var totalQuestions;
var currentSlide = 0;

Event.observe(window, 'load', function() {
	Event.observe('assessment', 'submit', checkAnswers);
	
	//$('assessment').getElementsBySelector('input[type="radio"]').each( function(item) {
	//	Event.observe(item, 'click', onQuestionAnswer.bindAsEventListener(this));
	//});
	assessmentGlider = new Glider('assessment-glider', {duration:0.5});
	var answeredQuestions = new Array();

	createSlider();
	setTotalQuestions();
});

function updateSlider(value) {

	value = slider.getNearestValue(value);
	if(value > currentSlide) {
		currentSlide++;
	} else if (value < currentSlide) {
		currentSlide--;
	}
	$("current_question").update(currentSlide+1);
	questions = $('assessment-glider').getElementsByClassName('question');
	assessmentGlider.moveTo(questions[currentSlide], $('scroller'), { duration: 0.5});
}

function checkAnswers(e) {
	var allAnswered = true;
	var questions = $('assessment').getElementsByClassName('question');

	questions.each(function (question) {
		if(!hasAnswer(question)) {
			allAnswered = false;
			new Effect.Highlight(question);
			//assessmentGlider.moveTo(question, assesmentGlider.scroller)
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
	if(currentSlide == 0) {
		currentSlide = totalQuestions;
	} else {
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
	if(currentSlide == totalQuestions-1) {
		currentSlide = 0;
	} else {
		currentSlide++;
	}
	
	if(currentSlide == totalQuestions-1) {
		$('assessment-next-button').hide();
	} else {
		$('assessment-previous-button').show();
	}
	
	assessmentGlider.next();
	slider.setValue(currentSlide);
	$("current_question").update(currentSlide+1);
	return false;
}