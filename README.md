MAKE SURE this project is inside the "htdocs" with "pharmanest" folder name (when using local)

DB Setup
1. Install XAMPP/WAMPP
2. In http://localhost/phpmyadmin/index.php?route=/server/variables search the "max_allowed_packet" key,
   click the "Edit", set it to 10485760 (10MB), and click "Save"
3. Create the pharmanest_db database
4. Import the "sqls/pharmanest_db.sql"

3rd Party Integration Setup
A. Sending Email via GMail
1. Download and Extract the https://github.com/PHPMailer/PHPMailer
2. Paste it to "apps" folder with "PHPMailer" folder name
