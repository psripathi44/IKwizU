/*This script validates if all questions answered in quiz or not*/ 
function quizFormValidation(){
	for(var i = 1 ; i <= 2 ; i++){
	  var choiceObjs = document.getElementsByName('Ques'+i);
	  var selected = false;
	  for (var j = 0, len = choiceObjs.length; j < len; j++){
		 if (choiceObjs[j].selected){
		  selected = true;
		  break;
		 }
	  }
	  if(!selected){
		 alert('Please answer Question No.'+i);
		 return false;
	  }
	}
	return true;
}