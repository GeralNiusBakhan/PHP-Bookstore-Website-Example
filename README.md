# PHP Basic BookStore Website (For Study Purpose Only)
This BookStore Website is using PHP and Database(MySQL). In this website you can Register and Edit Profile.
And also all the book data will store at the database for easy to add, edit and delete.

## Home Page & Edit Profile Page:
![HomePage](/homepage.PNG)
![EditProfile](/editprofile.PNG)

## DataBase:
![Database](/db.PNG)

## How to run:
Download [bookstore](https://github.com/weixiong15/PHP_Basic_BookStore_Website/tree/master/bookstore) folder and upload these file to your server or you can download an application called
[XAMPP](https://www.apachefriends.org/index.html) or other. After, you need to import [database.sql](https://github.com/weixiong15/PHP_Basic_BookStore_Website/blob/master/bookstore/database.sql) to your server/XAMPP 
first.
 
# Changelog

## [1.0.0] - 2024-06-03
### Added
- Implemented role-based access control for users and admins.
- Created `addbook.php` for admins to add books using OpenLibrary API.
- Introduced `add_admin.php` to facilitate the addition of admin users.
- Enhanced security with `password_hash` and `password_verify` for storing and verifying passwords.
- Prefilled user information during profile edits and checkout processes.

### Changed
- `connectDB.php`: Improved database connection by including both MySQLi and PDO options.
- `database.sql`: 
  - Added `Role` column to `Users` table to distinguish between users and admins.
  - Inserted sample data for admin users.
- `edituser.php`: 
  - Modified to include role-based logic, allowing admins to only change passwords and users to edit their full profiles.
  - Automatically populated form fields with current user information.
- `index.php`: 
  - Added role-based display logic to hide cart functionality and quantity inputs for admin users.
  - Enhanced cart management to include `CustomerID` and restrict access based on roles.
- `login.php`: 
  - Enhanced login process to set user roles in session.
  - Improved session management and error handling.
- `register.php`: 
  - Updated to include role assignment for new users.
  - Enhanced validation and security during the registration process.
- `checkout.php`: Prefilled user information during the checkout process.

### Fixed
- Various security improvements and bug fixes across multiple files to ensure better session management and data handling.

## [0.9.0] - 2024-05-01
### Added
- Initial release with basic functionalities for user registration, login, book display, cart management, and checkout process.

### Changed
- Basic validation and error handling for registration and login processes.

### Fixed
- Minor bug fixes in form validation and session management.
