# Laravel API- Registration, Login and Forgot Password verify Account 
##### api authentication with laravel passport

#### clone from repository 
git clone project url 

#### composer update 
#### php artisan migrate 
#### php artisan serve


### follow the api doc 


#API DOC 

## 1. Registration API 

### Method - POST

### Params 
name:jeremy
email:jeremy3@gmail.com
password:1234
confirm_password:1234
username:jerry

URL 
http://localhost:8000/api/registration


## 2. Login API 

### Method - POST

### Params 
email:jeremy3@gmail.com
password:1234

URL 
http://localhost:8000/api/login


## 3. Send Invite API 

### Method - POST

### Params 
email:s@gmail.com
### URL 
http://localhost:8000/api/invite


## 4. Edit Profile API 

### Method - POST

### Params 
name:sass
email:sss@gmail.com

## Authorization : Bearer Token in header

### URL 
http://localhost:8000/api/editprofile/{id}


## 5.Verify otp API 

### Method - POST

### Authorization Token {Bearer token}
### Middelware based session check 
### URL 
http://localhost:8000/api/verifyotp/=
