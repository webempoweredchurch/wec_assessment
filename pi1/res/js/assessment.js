Event.observe(window, 'load', function() {
	Event.observe('assessment', 'submit', checkAnswers);

	var answeredQuestions = new Array();
});


function checkAnswers(e) {
	var allAnswered = true;
	var questions = $('assessment').getElementsBySelector('.question');

	questions.each(function (question) {
		if(!hasAnswer(question)) {
			allAnswered = false;
			// Abusing Effect.Highlight to fade the color in but not back out.  Effect.Tween would be a better long term option.
			new Effect.Highlight(question, {startcolor: '#ffff99', endcolor: '#ffff99', restorecolor: '#ffff99'});
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

function nextQuestion() {
	// Placeholder function. Only used in slide interface.
}