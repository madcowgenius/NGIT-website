# Farm Produce and Livestock Tracker

WAD621S - Web Application Development  
Part-time Group 105  
Submission Date: October 19, 2025

## Team Members

- Tjatjitua Tjiyahura (221067264)
- Marcus Nicodemus (214021254)
- Hafeni Hashili (217130097)

---

## About the Project

This is a web application for helping Namibian farmers track their farm produce, livestock, and sales. We built it as our final project for the Web Application Development course.

The system lets farmers register an account, add their crops and animals, record sales, and see statistics about their farm.

## Problem We're Solving

Many small farmers in Namibia still use paper to keep records of what they planted, how many animals they have, and what they sold. This leads to:
- Lost records
- Forgotten planting dates
- Not knowing if they're making profit or loss
- Difficulty planning for next season

Our solution is a simple web app where everything is stored digitally and easy to access.

## Technologies Used

**Frontend:**
- HTML5 for page structure
- CSS3 for styling and layout
- JavaScript for making pages interactive

**Backend:**
- PHP for server-side logic
- api.php handles all requests from the frontend

**Database:**
- Microsoft SQL Server Express
- 6 tables: users, crops, livestock, sales, harvests, inventory
- 13 stored procedures for database operations

**Tools:**
- Visual Studio Code for writing code
- GitHub for version control
- PHP built-in server for testing

## Why SQL Server Instead of MySQL

Our original proposal said we would use MySQL, but we changed to SQL Server because:

1. We're taking Database Programming course which teaches SQL Server
2. We wanted to learn stored procedures properly
3. SQL Server is used by many companies in Namibia (FNB, Telecom)
4. It was already installed on our computers

This change made the project more challenging but we learned a lot more.

## Features

### What Works:
- User registration with password security
- User login and logout
- Dashboard showing total crops, livestock, sales, and revenue
- Add, view crops (maize, mahangu, wheat, vegetables)
- Add, view livestock (cattle, goats, chickens, sheep)
- Record sales with buyer information
- Calculate totals automatically
- Works on desktop and mobile

### What We Couldn't Complete:
- Full reports page (just basic structure)
- Harvest tracking (database ready but not connected to UI)
- Inventory management (planned for future)

## Database Structure

**users table** - stores farmer accounts  
**crops table** - stores crop planting records  
**livestock table** - stores animal records  
**sales table** - stores sales transactions  
**harvests table** - for future harvest tracking  
**inventory table** - for future inventory features  

We created 13 stored procedures:
- sp_RegisterUser, sp_LoginUser (authentication)
- sp_GetDashboardStats (dashboard statistics)
- sp_AddCrop, sp_GetCrops, sp_UpdateCrop, sp_DeleteCrop (crop management)
- sp_AddLivestock, sp_GetLivestock, sp_UpdateLivestock, sp_DeleteLivestock (livestock)
- sp_AddSale, sp_GetSales (sales tracking)

## How to Run

### Requirements:
- PHP 8 or higher
- Microsoft SQL Server Express
- PHP SQL Server drivers installed (sqlsrv, pdo_sqlsrv)

### Steps:
1. Make sure SQL Server is running
2. Create database called farm_tracker
3. Run stored procedures script (if provided)
4. Open terminal in project folder
5. Run: php -S localhost:8000
6. Open browser to http://localhost:8000
7. Register an account and start using

## File Structure

```
WAD/
├── api.php - Backend API
├── index.html - Landing page
├── about.html - About page
├── login.html - Login page
├── register.html - Registration page
├── dashboard.html - Dashboard
├── crops.html - Crop management
├── livestock.html - Livestock tracking
├── sales.html - Sales recording
├── reports.html - Reports page
├── README.md - This file
├── css/style.css - All styling
├── js/app.js - JavaScript code
└── images/ - Image files
```

## What We Learned

### Technical Skills:
- How to connect PHP to SQL Server (took 2 days to figure out instance names!)
- Writing and calling stored procedures
- Using PDO for database operations
- Building a REST-like API with PHP
- JavaScript fetch() for API calls
- Password hashing for security
- Form validation
- Responsive CSS layout

### Challenges We Faced:
1. SQL Server connection string - kept getting "server not found" errors
2. PHP SQL Server drivers - had to install sqlsrv extensions
3. Stored procedure syntax - T-SQL is different from regular SQL
4. Getting PDO to work with stored procedures - needed SET NOCOUNT ON
5. JavaScript promises and async/await - watched many YouTube tutorials
6. Making forms submit via JavaScript instead of regular form submission

### Resources We Used:
- W3Schools for HTML, CSS, JavaScript basics
- PHP documentation for PDO and database functions
- Microsoft SQL Server documentation
- YouTube tutorials for PHP and SQL Server connection
- Stack Overflow for debugging specific errors
- Course lecture notes from WAD621S and Database Programming

## Security Features

- Passwords are hashed using bcrypt (never stored in plain text)
- Prepared statements prevent SQL injection attacks
- Input sanitization to prevent XSS attacks
- User sessions for authentication

## Limitations

As stated in our proposal:
- Web-only, no mobile app
- No offline functionality
- No advanced analytics or machine learning
- No payment gateway integration
- Basic reports only

## Future Improvements

If we had more time, we would add:
- Better reports with charts
- Export data to PDF or Excel
- SMS notifications for important dates
- Multi-language support (Oshiwambo, Afrikaans)
- Weather information
- Market price tracking

## Testing

We tested:
- User registration works
- Login authentication works
- Dashboard shows correct statistics
- Can add crops and see them in table
- Can add livestock and view records
- Can record sales
- All pages display correctly on mobile

## Acknowledgments

Thanks to our lecturers for teaching us web development and database programming. Thanks to our families for supporting us during late night coding sessions.

Special thanks to farmers who inspired this project.

---

Project Status: Complete  
Last Updated: October 19, 2025  
Course: WAD621S  
Group: Part-time Group 105
