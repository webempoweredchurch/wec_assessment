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
			new Effect.Highlight(question);
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