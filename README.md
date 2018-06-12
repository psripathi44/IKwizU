# IKwizU
IKwizU is a web application, that you can use to challenge your friends with a quiz.
First you take the quiz and then you can challenge your friends my sharing the same via Email/Messenger/etc, 

This web-app works by consuming the <a href="https://opentdb.com/" target="_blank">OpenTriviaDB</a> API.
All you need to do is select the Category, Difficulty, and Type of questions(Multiple Choice or Boolean). 
Basis your inputs, the url for APT is constructed and fetched from OpenTriviaDB.

The idea was to display the final score to the user, but the enhancement would be to store the quiz questions in a buffer table temporarily which enables us to not only display the total score, but also the question along with the correct answer and the user selection.
