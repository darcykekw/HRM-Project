# How the Code Works

## 1. Project Structure
I organized the code into folders to keep things clean:
*   **`classes/`**: This is where the main logic lives. I used Object-Oriented Programming (OOP) so I could reuse code easily.
*   **`config/`**: Holds the database settings.
*   **`views/`**: These are the actual pages the user sees (forms, tables, reports).
*   **`index.php`**: The login page.

## 2. Database Connection (`classes/Database.php`)
Instead of writing the MySQL connection code in every single file, I created a `Database` class.
*   It uses **PDO (PHP Data Objects)** instead of `mysqli`.
*   I chose PDO because it allows me to use **Prepared Statements**, which protects the site from SQL Injection attacks.
*   Whenever a page needs to talk to the database, it just calls `$database->getConnection()`.

## 3. Authentication (`classes/Auth.php`)
Handling logins is done in the `Auth` class.
*   **Login**: When a user tries to log in, I check their username in the database.
*   **Password Hashing**: I don't store plain passwords. I use `password_verify()` to check the input against the hashed password stored in the database.
*   **Sessions**: Once logged in, I save their `user_id`, `username`, and `role` in the PHP `$_SESSION`. This lets me check if they are logged in on every page.
*   **Role Checks**: I created a function `requireRole()` that I put at the top of restricted pages (like `delete_employee.php`). If a user doesn't have the right role (like 'HR Head'), it kicks them out.

## 4. Employee Management (`classes/Employee.php`)
This is the biggest class in the project. It handles everything related to employees.
*   **CRUD**: It has functions for `create`, `read`, `update`, and `delete`.
*   **Transactions**: In the `create` function, I used `beginTransaction()` and `commit()`. This is important because if something goes wrong while saving (like the internet cuts out), it `rollBack()` everything so we don't get half-saved data.
*   **Activity Logs**: Inside the `create`, `update`, and `delete` functions, I pass the current user's ID to MySQL variables (`SET @current_user_id`). This way, the database triggers know exactly *who* made the change.

## 5. The Frontend (Views)
The files in the `views/` folder (like `add_employee.php`) are a mix of PHP and HTML.
*   **Bootstrap**: I used Bootstrap 4 for the design. It makes the forms and tables look good and work on mobile without writing a ton of custom CSS.
*   **Logic**: At the top of each view file, I include the classes, check if the user is logged in, and then handle any form submissions (POST requests).

## 6. Security Measures
*   **SQL Injection**: Since I used `prepare()` and `bindParam()` everywhere, users can't hack the database by typing weird code into the login box.
*   **XSS**: When displaying data, I try to be careful about what gets printed to the screen.
*   **Access Control**: You can't just type `delete_employee.php` in the URL to delete someone. The code checks your session role first.

## 7. Reporting System (`views/reports.php`)
For the reports, I wrote direct SQL queries in the view file to get the specific data we needed.
*   **Aggregating Data**: I used the SQL `GROUP BY` command to count employees. For example, `SELECT department, COUNT(*) ... GROUP BY department` gives me the numbers for the "Employees by Department" table.
*   **Service Length**: For the "Length of Service" report, I simply pulled the list of employees and ordered them by `date_hired` so you can see who has been here the longest.

## 8. Search Functionality
In `list_employees.php`, there is a search bar.
*   When you type a name and hit enter, it sends a `GET` request (like `?search=Juan`).
*   The code passes this search term to the `Employee->readAll($search)` function.
*   Inside that function, I used SQL `LIKE` operators (e.g., `WHERE surname LIKE %Juan%`) to find matches in the ID, First Name, or Surname columns.

## 9. Database Automation (Triggers)
One of the coolest parts is how the database handles its own logging.
*   **Setup**: The `install.php` script creates "Triggers" in the database. These are like mini-programs that live inside MySQL.
*   **Execution**: Whenever PHP adds, edits, or deletes an employee, the Trigger immediately wakes up and writes a row into the `activity_logs` table.
*   **Connection**: PHP helps out by telling MySQL *who* is doing the action (`SET @current_user_id`) right before the change happens, so the Trigger can record the username.

## 10. Handling Complex Forms (CSC Form 212)
The Personal Data Sheet (PDS) has a huge amount of data.
*   **Data Handling**: In `add_employee.php`, I collect dozens of fields (from "Blood Type" to "Questionnaire" answers).
*   **Validation**: I check for required fields (like Name and Birthdate) before sending anything to the database.
*   **Normalization**: Even though the form is huge, I tried to keep the database table organized, using appropriate data types (like `DATE` for birthdays and `ENUM` for Yes/No questions) instead of making everything a text field.
