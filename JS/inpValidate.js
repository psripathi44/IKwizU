isValid = false;

function quizInpValidation(evt){	
	if(validateHere()){}
		return isValid;
}
	

  // Create a request variable and assign a new XMLHttpRequest object to it.
  function validateHere(){
	var inpCategory = document.getElementById('inpCategory'),
	category = inpCategory.value;
	
	var inpDifficulty = document.getElementById('inpDifficulty'),
	difficulty = inpDifficulty.value;
	
	var inpQtype = document.getElementById('inpQtype'),
	qtype = inpQtype.value;
	
	var url = 'https://opentdb.com/api.php?amount=20';

	if(category != 'any')
		url += '&category='+category;
		
	if(difficulty != 'any')
		url += '&difficulty='+difficulty;
	
	if(qtype != 'any')
		url += '&type='+qtype;
	
	fetch(url, {  //passing the url based on the inputs
	  method: 'GET'
	})
	.then(function(response){ 
		return response.json(); 
	})
	.then(function(json) {
		  //console.log(json.response_code); //response_code is trivia api json element
		  if(json.response_code == 1){
			  document.getElementById('errContainer').innerHTML = "Oops: The API wasn't able to fetch questions based on the selected inputs. Please try again.";
			  isValid = false;
			  return isValid;
		  } else {
			  isValid = true;
			  return isValid;
		  }
	  });
	/*
    Code 0: Success Returned results successfully.
    Code 1: No Results Could not return results. The API doesn't have enough questions for your query. (Ex. Asking for 50 Questions in a Category that only has 20.)
    Code 2: Invalid Parameter Contains an invalid parameter. Arguements passed in aren't valid. (Ex. Amount = Five)
    Code 3: Token Not Found Session Token does not exist.
    Code 4: Token Empty Session Token has returned all possible questions for the specified query. Resetting the Token is necessary.*/
}