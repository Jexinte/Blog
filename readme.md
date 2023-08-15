# Description

This project is about creating a blog where users can read and comment some articles published by admins that handle comments validation and others things : 


# Installation

1 - Clone the repo

2 - Use the package manager [composer](https://getcomposer.org/doc/00-intro.md) to install packages.
```
composer install
```

# Configure your GMAIL SMTP

An SMTP is used for the homepage form contact and when a user send a comment it will warn you that you have to handle them through the admin panel so you have to configure it so just follow the steps below or you can follow this tutorial too if you want [How to configure SMTP GMAIL](https://www.youtube.com/watch?v=yuOK6D7deTo) : 

 Update the content of the following file `stmp_credentials_example.json` here how to do it :

 - Log in to your account [GMAIL](https://gmail.com)
 - Go to your profile && select the `Security" option`
 - Select the `Two-Step Verification` && `App Passwords`
 - On `Select an application` choose `Other` and write whatever you want
 - Click on `Generate` && You'll get a password copy it and replace `password application` by the generate one
 - Replace `youremail@example.com` by your own gmail address
 - Replace `smtp` by `smtp.gmail.com` && remove the word `example` from `smtp_credentials_example.json`



# Folder

Create the following folder :
`user_profile` on `images` folder

# Database & User

Create a database called `professional_blog` and insert the `professional_blog.sql` in phpmyadmin or any other web app using mysql

You have to create an `admin` user `DO IT ON THE WEB APP` then when created you have to change the type of the user for example in phpymadmin when the user is created go to edit him near the pencil of the actual user then at the bottom of the window you'll have two type of users for the `type` field and select `admin` then next time you'll log the access for the admin panel will be granted



