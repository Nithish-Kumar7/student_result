

# Student Result Management

This project is a **PHP-based web application** that allows students to log in and view their semester results. The login process requires students to enter their roll number, date of birth, and a CAPTCHA code for authentication.

## Features

- **Student Login**: Secure login using roll number and date of birth.
- **CAPTCHA Verification**: Simple CAPTCHA to prevent automated login attempts.
- **Result Display**: Once logged in, students can view their semester results (subject-wise marks).
- **Responsive Design**: The application layout is mobile-friendly and adapts to different screen sizes.

## Prerequisites

To run this project locally, you will need:

- **PHP** (Version 7.0 or higher)
- **MySQL** Database
- **Apache** or any other server with PHP support (e.g., XAMPP, WAMP)
- **Web browser** (Chrome, Firefox, etc.)

## Setup Instructions

1. **Clone the repository**:

   ```
   git clone https://github.com/your-username/student-result-management.git
   cd student-result-management
   ```

2. **Database Setup**:

   - Create a MySQL database named `students_result`.
   - Use the database text file to import the database onto your phpMyAdmin.
   
   

3. **Configure the Database Connection**:

   - In the `index.php` file, update the database connection details:
     ```php
     $servername = "localhost"; 
     $username = "root"; 
     $password = ""; 
     $dbname = "students_result"; 
     $port = "3307"; // Or 3306 if using the default MySQL port
     ```

4. **Run the Application**:

   - Start your Apache server and navigate to the project directory in your browser:
     ```
     http://localhost/student-result-management
     ```

5. **Login**:

   - Enter the roll number, date of birth, and CAPTCHA to log in and view semester results.

## Project Structure

- `result.php`: The main file that handles student login and result display.
- `README.md`: Instructions on how to set up and use the project.

## Screenshots

1. **Login Page**:
   ![Screenshot 2024-10-10 093004](https://github.com/user-attachments/assets/d03d49d2-9d30-41fa-95c6-f7cc06d90b11)

  
2. **Result Page**:
   ![Screenshot 2024-10-10 093145](https://github.com/user-attachments/assets/2f3121e2-b7f8-4f1c-9b08-1edf3667937c)

## Future Enhancements

- Admin panel to upload and manage student results.
- Enhanced CAPTCHA using third-party services (e.g., Google reCAPTCHA).
- Add password encryption for better security.

## License

This project is licensed under the MIT License.

