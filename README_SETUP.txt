STUDENTHUB SETUP

1. Keep your original WT folder as your backup. This package preserves every original file and adds new files for secure login, student management, event management, and APIs.
2. Extract the ZIP. Rename the extracted StudentHub_Completed folder to StudentHub and place it at C:\xampp\htdocs\StudentHub.
3. Start Apache and MySQL from XAMPP.
4. Open http://localhost/StudentHub/setup.php.
5. After setup completes, sign in with admin@studenthub.local and password Admin@123.
6. Student registration is available from Register.html; the Apache rewrite sends that URL to secure-register.php while preserving the original Register.html file.
7. Login.html is routed to secure-login.php, DashBoard.html to student-dashboard.php, and Profile.html to student-profile.php through .htaccess.
8. Students register through the new PHP registration page. Existing users in the old newcollege database are not automatically copied into the new studenthub database.
9. Delete setup.php after database setup. Change the default admin password before using the project beyond a local practical demonstration.
10. The session timeout is five minutes of inactivity. The Remember Me token is not implemented; it is optional in the practical brief and should only be added with a database-backed random token stored as a hash.
11. Original HTML, CSS, JavaScript, and PHP practical files were retained unchanged. The new secure pages use the existing Style.css for the same general site styling.
12. Apache mod_rewrite must be enabled for the original Login.html, Register.html, DashBoard.html and Profile.html URLs to route to their secure PHP versions. If .htaccess rewriting is disabled, open secure-login.php and secure-register.php directly.

13. A StudentHub logo has been added as logo.svg and is displayed beside existing header titles using Style.css.
14. The home page Script.js is loaded with defer so its buttons initialize after the page elements exist.
15. PHP files were checked with PHP syntax lint; all PHP files passed. Database-backed behavior still needs to be tested on your own XAMPP installation.
