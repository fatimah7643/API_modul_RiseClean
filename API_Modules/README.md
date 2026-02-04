# RiseClean API Documentation

This API provides CRUD operations for all modules in the RiseClean system based on the database tables.

## Available Modules

1. Users
2. Education Modules
3. Challenges
4. Quiz Questions
5. Quiz Choices
6. User Progress
7. User Quiz Answers
8. Rewards
9. User Rewards
10. Activity Logs
11. Levels
12. Roles
13. Failed Login Attempts
14. Login Attempts

## API Endpoints

### Users
- **GET** `/API_Modules/Users/read.php` - Get all users or specific user by ID
- **POST** `/API_Modules/Users/create.php` - Create a new user
- **POST** `/API_Modules/Users/update.php` - Update an existing user
- **POST** `/API_Modules/Users/delete.php` - Delete a user

### Education Modules
- **GET** `/API_Modules/EducationModules/read.php` - Get all education modules or specific module by ID
- **POST** `/API_Modules/EducationModules/create.php` - Create a new education module
- **POST** `/API_Modules/EducationModules/update.php` - Update an existing education module
- **POST** `/API_Modules/EducationModules/delete.php` - Delete an education module

### Challenges
- **GET** `/API_Modules/Challenges/read.php` - Get all challenges or specific challenge by ID
- **POST** `/API_Modules/Challenges/create.php` - Create a new challenge
- **POST** `/API_Modules/Challenges/update.php` - Update an existing challenge
- **POST** `/API_Modules/Challenges/delete.php` - Delete a challenge

### Quiz Questions
- **GET** `/API_Modules/QuizQuestions/read.php` - Get all quiz questions or specific question by ID
- **POST** `/API_Modules/QuizQuestions/create.php` - Create a new quiz question
- **POST** `/API_Modules/QuizQuestions/update.php` - Update an existing quiz question
- **POST** `/API_Modules/QuizQuestions/delete.php` - Delete a quiz question

### Quiz Choices
- **GET** `/API_Modules/QuizChoices/read.php` - Get all quiz choices or specific choice by ID
- **POST** `/API_Modules/QuizChoices/create.php` - Create a new quiz choice
- **POST** `/API_Modules/QuizChoices/update.php` - Update an existing quiz choice
- **POST** `/API_Modules/QuizChoices/delete.php` - Delete a quiz choice

### User Progress
- **GET** `/API_Modules/UserProgress/read.php` - Get all user progress records or specific record by ID
- **POST** `/API_Modules/UserProgress/create.php` - Create a new user progress record
- **POST** `/API_Modules/UserProgress/update.php` - Update an existing user progress record
- **POST** `/API_Modules/UserProgress/delete.php` - Delete a user progress record

### User Quiz Answers
- **GET** `/API_Modules/UserQuizAnswers/read.php` - Get all user quiz answers or specific answer by ID
- **POST** `/API_Modules/UserQuizAnswers/create.php` - Create a new user quiz answer
- **POST** `/API_Modules/UserQuizAnswers/update.php` - Update an existing user quiz answer
- **POST** `/API_Modules/UserQuizAnswers/delete.php` - Delete a user quiz answer

### Rewards
- **GET** `/API_Modules/Rewards/read.php` - Get all rewards or specific reward by ID
- **POST** `/API_Modules/Rewards/create.php` - Create a new reward
- **POST** `/API_Modules/Rewards/update.php` - Update an existing reward
- **POST** `/API_Modules/Rewards/delete.php` - Delete a reward

### User Rewards
- **GET** `/API_Modules/UserRewards/read.php` - Get all user rewards or specific user reward by ID
- **POST** `/API_Modules/UserRewards/create.php` - Create a new user reward
- **POST** `/API_Modules/UserRewards/update.php` - Update an existing user reward
- **POST** `/API_Modules/UserRewards/delete.php` - Delete a user reward

### Activity Logs
- **GET** `/API_Modules/ActivityLogs/read.php` - Get all activity logs or specific log by ID
- **POST** `/API_Modules/ActivityLogs/create.php` - Create a new activity log
- **POST** `/API_Modules/ActivityLogs/update.php` - Update an existing activity log
- **POST** `/API_Modules/ActivityLogs/delete.php` - Delete an activity log

### Levels
- **GET** `/API_Modules/Levels/read.php` - Get all levels or specific level by ID
- **POST** `/API_Modules/Levels/create.php` - Create a new level
- **POST** `/API_Modules/Levels/update.php` - Update an existing level
- **POST** `/API_Modules/Levels/delete.php` - Delete a level

### Roles
- **GET** `/API_Modules/Roles/read.php` - Get all roles or specific role by ID
- **POST** `/API_Modules/Roles/create.php` - Create a new role
- **POST** `/API_Modules/Roles/update.php` - Update an existing role
- **POST** `/API_Modules/Roles/delete.php` - Delete a role

### Failed Login Attempts
- **GET** `/API_Modules/FailedLoginAttempts/read.php` - Get all failed login attempts or specific attempt by ID
- **POST** `/API_Modules/FailedLoginAttempts/create.php` - Create a new failed login attempt
- **POST** `/API_Modules/FailedLoginAttempts/update.php` - Update an existing failed login attempt
- **POST** `/API_Modules/FailedLoginAttempts/delete.php` - Delete a failed login attempt

### Login Attempts
- **GET** `/API_Modules/LoginAttempts/read.php` - Get all login attempts or specific attempt by ID
- **POST** `/API_Modules/LoginAttempts/create.php` - Create a new login attempt
- **POST** `/API_Modules/LoginAttempts/update.php` - Update an existing login attempt
- **POST** `/API_Modules/LoginAttempts/delete.php` - Delete a login attempt

## How to Test in Postman

1. Make sure your web server (Apache/Nginx) is running
2. Access the API endpoints using the URLs above
3. For GET requests, you can pass the ID parameter in the URL as `?id=X` or in the request body
4. For POST requests, use `x-www-form-urlencoded` format to send data
5. All APIs return JSON responses with the format:
   ```json
   {
     "status": "success|error",
     "message": "Description of the result",
     "data": {...} // Present in successful responses
   }
   ```

## Example Requests

### Creating a User
- Method: POST
- URL: `/API_Modules/Users/create.php`
- Body (x-www-form-urlencoded):
  - username: "john_doe"
  - email: "john@example.com"
  - password: "secure_password"
  - first_name: "John"
  - last_name: "Doe"

### Reading a Specific User
- Method: GET
- URL: `/API_Modules/Users/read.php?id=1`

### Updating a User
- Method: POST
- URL: `/API_Modules/Users/update.php`
- Body (x-www-form-urlencoded):
  - id: 1
  - username: "john_updated"
  - email: "john.updated@example.com"

### Deleting a User
- Method: POST
- URL: `/API_Modules/Users/delete.php`
- Body (x-www-form-urlencoded):
  - id: 1