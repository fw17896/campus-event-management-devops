CAMPUS EVENT MANAGEMENT SYSTEM
================================

Modules:
1. Student Management - add/list/delete students
2. Event Management - add/list/delete events
3. Registration Management - register/cancel student registrations
4. Dashboard - counts and upcoming events

Technology:
PHP + Apache + MySQL

LOCAL/EC2 DATABASE:
Database: campus_events
Application DB user: appuser
Password: set your own password in config.php

IMPORTANT:
1. Import database/init.sql into MySQL.
2. Create appuser and grant it privileges on campus_events.
3. Edit config.php and replace CHANGE_ME with the database password.
4. Put the application under /var/www/html.
