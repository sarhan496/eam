##########################################################################################################################
Enterprise Asset Management - Sarhan Yaakob - https://www.linkedin.com/in/mohamad-sarhan-yaakob-019106b3/
Version: 3.0
Date: 04/13/2023
License: free with credit in source code, any other use forbidden
Requirements:PHP 7, VScode or higher to modify the application
Support: you shouldn't need any but...
	- If you find a bug in the application or setup I will work to fix it
	- If you need help contact me for availability and rates
Contact:  https://www.linkedin.com/in/mohamad-sarhan-yaakob-019106b3/
##########################################################################################################################

--// UPGRADE STEPS from version 2.0 installs //--

1. Backup existing Database & files!! 
2. Dude, step #1...
3. See /Assets/Changelog.txt for details of change files
4. Run SQL Upgrade Script: /Assets/DatabaseUpdate2-3.sql
   - phpMyAdmin: Go the Import tab and tab and browse to /Assets/DatabaseUpdate2-3.sql, click GO.
5. Overwrite existing files:
   - /includes/header.php
   - /logout.php
   - /HardwareAdd.php
   - /HardwareView.php
   - /HardwareUpdate.php

--\\ END Upgrade steps \\--



--// NEW INSTALL SETUP STEPS //--

1. Unzip files to: /eam or folder of your choosing
	/_mmServerScripts - don't touch! required for the app to run.
	/notes - dreamweaver project files
	/Assets - Database SQL file, Logo files (Photoshop and layered PNG) 
	/Connections - Database connector (see step 6)
	/images - images...
	/includes - required files used throughout the app
	/  -  all app files
	
2. Read this file :-)

3. Create Database and database user
	- Use CPanel, Microsoft SQL Server Management Studio or similar tool to create a Database and Database User
	
4. Import Database Structure 
	- Use '/Assets/eam.sql' to create tables, admin user and sample data 
	- Use phpMyAdmin, MS SQL Admin Studio or similar tool to open the Database you created in step 3
		- Import the file. phpMyAdmin: Go the Import tab and tab and browse to /eam/Assets/eam.sql, click GO.
		or
		- Execute the SQL code. Open /eam/Assets/eam.sql, copy all contents. In phpMyAdmin click the SQL tab, paste in the contents and click go.
	
5. Edit Database Configuration for your environment 
	- Edit /connections/eam.php
	- Edit Database name, username & password  (Change these to match your Database!)
		$hostname_eam = "localhost";  // MYSQL database host adress - do not Change!
		$database_eam = "eam"; // MYSQL database name
		$username_eam = "eam"; // Mysql Datbase user
		$password_eam = "YourDBPassword"; // Mysql Datbase password
	
6. Edit CSV Export Reports
	- /eam/includes/dbConnecExportCSV.php
	- Edit Database name, username & password  (Change these to match your Database!)
		$host = 'localhost'; // MYSQL database host adress
		$db = 'eam'; // MYSQL database name
		$user = 'eam'; // Mysql Datbase user
		$pass = 'YourDBPassword'; // Mysql Datbase password
	
7. Place all files on server

8. Login as admin or editor (sample accounts)
	Admin = eam + eam123
	Editor = dude + dude
	
9. Update Admin Data
	1. Company name
	2. Admin and Editor sample accounts
	
10. Have fun!

--\\ End New Install Steps \\--


##########################################################################################################################
 
DREAMWEAVER 
	- http://www.adobe.com/support/documentation/en/dreamweaver/

##########################################################################################################################


https://www.gfisk.com/it-asset-management-database-free-download/

