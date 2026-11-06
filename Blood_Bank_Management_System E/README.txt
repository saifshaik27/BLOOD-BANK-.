BLOOD BANK MANAGEMENT SYSTEM - MINI PROJECT
============================================

TECHNOLOGIES
- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Local server: XAMPP

HOW TO RUN
1. Install XAMPP and VS Code.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Copy the folder "Blood_Bank_Management_System" into:
   C:\xampp\htdocs\
4. Open http://localhost/phpmyadmin in your browser.
5. Click Import, choose database.sql from this project folder, and click Go.
   (The SQL file creates the database and three tables.)
6. Open http://localhost/Blood_Bank_Management_System/setup_admin.php once.
7. You will see confirmation that the admin account is ready.
8. IMPORTANT: Delete setup_admin.php after running it once.
9. Open http://localhost/Blood_Bank_Management_System/login.php

DEMO LOGIN
Email: admin@bloodbank.com
Password: admin123

HOW TO DEMONSTRATE
1. Login with the demo admin account.
2. Open Blood Stock and update units for a blood group.
3. Open Blood Requests and add a hospital request.
4. Click Approve. If enough stock is available, the units are deducted.
5. Add another request and click Reject to demonstrate rejection.
6. Return to Dashboard to view total units and pending requests.

IMPORTANT NOTES
- This is an educational mini project, not a production blood-bank system.
- Only approve a request when the blood stock is sufficient.
- The system uses a simple admin login for demonstration.
- For a real deployment, add CSRF protection, role management, audit logs,
  HTTPS, input policies, and appropriate blood-bank medical workflows.
- If your MySQL root account has a password, update $password in config.php.
