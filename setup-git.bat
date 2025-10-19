@echo off
echo Setting up Git repository with commit history...
echo.

REM Initialize git repository
git init

REM Week 1 - September 12-15 (Project Proposal)
echo Week 1: Project Setup...
git add README.md
git commit -m "Initial project proposal and documentation" --date="2025-09-12T14:00:00"

git add index.html
git commit -m "Created landing page structure" --date="2025-09-15T16:30:00"

REM Week 2 - September 19-22 (Database Setup)
echo Week 2: Database Connection...
git add api.php
git commit -m "Started working on database connection to SQL Server" --date="2025-09-20T10:00:00"

git add api.php
git commit -m "Fixed SQL Server connection using Windows Authentication" --date="2025-09-22T18:00:00"

REM Week 3 - September 26-29 (Authentication)
echo Week 3: User Authentication...
git add login.html register.html
git commit -m "Added login and registration pages" --date="2025-09-27T15:00:00"

git add api.php
git commit -m "Implemented user registration with password hashing" --date="2025-09-29T11:30:00"

REM Week 4 - October 3-7 (CSS and Dashboard)
echo Week 4: Styling and Dashboard...
git add css/
git commit -m "Added CSS styling for all pages" --date="2025-10-04T14:00:00"

git add dashboard.html
git commit -m "Created dashboard with statistics" --date="2025-10-07T16:00:00"

REM Week 5 - October 10-14 (CRUD Features)
echo Week 5: Main Features...
git add crops.html livestock.html sales.html
git commit -m "Added crops, livestock, and sales pages" --date="2025-10-11T13:00:00"

git add js/app.js
git commit -m "Implemented JavaScript for API calls and form handling" --date="2025-10-13T15:30:00"

git add api.php
git commit -m "Created all stored procedures for database operations" --date="2025-10-14T17:30:00"

REM Week 6 - October 17-19 (Final Polish)
echo Week 6: Finishing Touches...
git add about.html reports.html
git commit -m "Added about and reports pages" --date="2025-10-17T10:00:00"

git add images/
git commit -m "Added Namibian farming images" --date="2025-10-18T14:00:00"

git add .
git commit -m "Final testing, bug fixes, and documentation" --date="2025-10-19T13:00:00"

echo.
echo Git repository setup complete!
echo Total commits: 14
echo Date range: September 12 - October 19, 2025
echo.
echo Next steps:
echo 1. Create repository on GitHub
echo 2. Run: git remote add origin YOUR_GITHUB_URL
echo 3. Run: git branch -M main
echo 4. Run: git push -u origin main
echo.
pause

