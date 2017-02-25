
Language of choice was PHP.
A PHP server. XAMPP was used for development.
For the assignment to run you'll need to create an additional user named "db_user" in MySQL.
If you're creating the user and an error is thrown, you'll need to ensure that this path exists "C:\xampp\mysql\lib\plugin".
The table can be created by opening and copying the createTable.sql



The script contains a textbox and three buttons. Each button activates a different functionality
Get Data retrieves all pages counting from the very first page (page 0) through to amount that was specified in the input field.
If the amount specified is more than the number of pages then the amount will default to the maximum number from the data source.
Delete Data removes all information from the database.
Answer query answers the question that given in the assignment.
Insert All Data retrieves all information that is available.

Feature enhancements
- Retrieval of pages based on a range (pages 3 - 5)
- Present the job data in a readable format
- Provide user with option to apply to those jobs
- Create more queries that can answer other questions
- Add option to retrieve jobs of a certain job type
