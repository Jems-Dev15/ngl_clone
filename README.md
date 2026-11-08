JM.NGL – Anonymous Messaging Website
JM.NGL is a PHP and MySQL-based anonymous messaging website. Users can create an account, sign in, share a personal profile link, and receive anonymous messages from visitors.
Features
- User registration with username, email, and password.
- Login using either username or email.
- Password hashing with PHP password_hash() and verification with password_verify().
- Session-based user authentication.
- Public profile pages using a username-based link.
- Anonymous message submission.
- Basic device identification from the visitor's browser user-agent.
- Basic word filtering for selected terms.
- Responsive landing page and profile interface.
- Dark/light theme controls on the pages where implemented.
Technologies Used
- PHP – server-side logic, authentication, and message handling.
- MySQL – stores user accounts and messages.
- PDO – database connection and prepared SQL statements.
- HTML5 – page structure.
- CSS3 – styling, animations, and responsive layouts.
- Tailwind CSS – utility classes loaded through its CDN.
- JavaScript – theme controls and interface interactions.
- Font Awesome – icons.
- Google Fonts – typography.
Project Files
The files supplied for this project are:
File	Purpose
index.php	Public landing page for JM.NGL.
login.php	Login and account registration page.
database.php	Creates the PDO connection to MySQL.
db.sql	Creates the database and its tables.
profile.php	Displays a user's public profile and anonymous message form.
send.php	Validates and saves submitted anonymous messages.
header.php	Shared HTML header and common styling setup.
footer.php	Shared footer markup.


Important: The uploaded landing-page file was named index(3).php; rename it to index.php. The uploaded authentication file was named login(1).php; rename it to login.php. The landing page and login code also link to dashboard.php, but that file was not included in the supplied files. Add the dashboard file or update those links before using the complete authenticated flow.
Database Structure
The SQL script creates a database named ngl_clone with two tables.
users
Column	Description
id	Auto-incrementing user ID and primary key.
username	Unique username.
email	Unique email address.
password	Hashed account password.
prompt_question	Text displayed on the public profile.
created_at	Account creation timestamp.


messages
Column	Description
id	Auto-incrementing message ID and primary key.
user_id	References the recipient in the users table.
message_text	Anonymous message content.
device_hint	Basic device category detected from the user-agent.
is_opened	Message-opened flag; defaults to 0.
created_at	Message submission timestamp.


The messages.user_id field references users.id. Deleting a user also deletes that user's messages through the foreign-key cascade.
Installation (XAMPP / Localhost)
1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Copy the project folder into:
   C:\xampp\htdocs\ngl-clone
4. Rename the supplied files:
   - index(3).php → index.php
   - login(1).php → login.php
5. Open phpMyAdmin:
   http://localhost/phpmyadmin
6. Select the Import tab and import db.sql.
7. Check the database connection settings in database.php. The supplied local settings are:
   $host = 'localhost';
   $db = 'ngl_clone';
   $user = 'root';
   $pass = '';
   $charset = 'utf8mb4';
8. Open the website:
   http://localhost/ngl-clone/
How to Use
1. Open the landing page.
2. Choose Create Account and register with a username, email, and password.
3. Sign in using the username or email and password.
4. Open the dashboard after the dashboard page has been added.
5. Share the public profile link. The profile URL uses this format:
   profile.php?u=USERNAME
6. Visitors can open the profile, read its prompt, and submit a message without signing in.
7. send.php stores the message and redirects the visitor back to the profile with a success indicator.
Deployment Notes
For hosting providers such as InfinityFree:
1. Upload the PHP files to the website's document root (commonly htdocs).
2. Create a MySQL database using the hosting control panel.
3. Import db.sql into the hosting database using the provider's database tool.
4. Update database.php with the database host, database name, database username, and database password supplied by the hosting provider.
5. Do not use the local XAMPP credentials (root with an empty password) on remote hosting.
6. Make sure the database name in database.php matches the database created by the hosting provider. Some providers require a username or account prefix in the database name.
7. Visit the hosted website and test registration, login, profile links, and message submission.
Security and Configuration Notes
- Passwords are stored as hashes, not as plain text, for accounts created through the registration form.
- Database operations use PDO prepared statements.
- The login flow regenerates the session ID after successful authentication.
- The current send.php applies a small hard-coded word replacement list. This is not a complete moderation or spam-prevention system.
- The device_hint value is inferred from the browser's user-agent and may be inaccurate. It should not be treated as reliable identification.
- Before public deployment, use HTTPS, add CSRF protection, validate and rate-limit message submissions, and avoid displaying raw database errors to visitors.
- Review the sample account inserted by db.sql. Replace or remove sample credentials before deploying publicly.
Troubleshooting
Database connection failed
- Confirm that MySQL is running locally or that the hosting database service is available.
- Check all database credentials in database.php.
- On remote hosting, use the database host shown in the hosting control panel, not necessarily localhost.
- Confirm that the database and tables were created successfully.
Page not found
- Check that the project is inside the correct web root.
- Confirm that index(3).php and login(1).php were renamed as described above.
- Check that links point to files that actually exist. In particular, dashboard.php is referenced but was not among the supplied files.
Login does not work
- Confirm that the users table exists.
- Register a new account through login.php?mode=register.
- Check that the account's password was saved as a hash.
- Make sure PHP sessions are enabled.
Current Project Limitation
The supplied files include the landing page, authentication, database setup, public profile, and message submission. They do not include dashboard.php, a logout page, or a page for viewing/managing received messages. Those features should be added if they are part of the intended final system.
