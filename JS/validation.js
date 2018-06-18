/*This script validates if all questions answered in quiz or not*/ 
function quizFormValidation(){
	for(var i = 1 ; i <= 15 ; i++){
		var selected = false;
		var choiceObjs = document.getElementsByName("Ques"+i);
		for (var j = 0, len = choiceObjs.length; j < len; j++) {
			if (choiceObjs[j].checked){
				selected = true;
				break;
			}
				
		}
		if(!selected){
			alert('Please answer Question No.'+i);
			return false;
		}
	}
}