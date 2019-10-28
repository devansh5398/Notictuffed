# Notictuffed
Notictuffed is an e-noticeboard where a user can access all the notices posted by the admins.
It uses HTML, CSS, Bootstrap, jQuery, PHP and MySQL.

Project works in two modes: Admin and Student.
* Admin: Admins have the privilege of posting a new notice, see their posted notices separately and updating their profile.
* Student: Students can only update their profile.
  
The students are required to CREATE AN ACCOUNT by filling following details :
* Firstname: should only contain alphabets. (Mandatory)
* Lastname:  should only contain alphabets. (Optional)
* Username: should be unique and alphabets, digits, and special characters (. _ -) are allowed. (Mandatory)
* Password: should contain 8 to 30 characters and can't start or end with blank space. (Mandatory)
* confirm: should be same as password. (Mandatory)
* E-mail: should be a valid e-mail. (Mandatory)
To verify the entered email an activation code is send which remains valid for 2 minutes.

Admin’s accounts are beforehand created so that this privilege is given to only selected people.

Admins and students log in using their username/email and password.

Option of ‘Forgot Password’ is also available which allows you to reset your account by sending password reseting code to your registered e-mail address.

Logged in home page shows all notices from all admins with most recent one at the top.

You can grab some of the accounts of Admins as well as Students created beforehand from 'Username & Password' file provided.
