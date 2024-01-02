## Video
```ch
https://drive.google.com/drive/folders/1lkFgGTXFh3RB5bGD97YfMA2a4kO0lkkb?usp=drive_link
```

## Instalation


#### clone the repo

#### composer update
```ch
composer update
npm install
npm run dev
```

#### migrate the table
```ch
DB_DATABASE=your_database_name
```
```ch
php artisan migrate
```

#### run the site

```ch
php artisan serve
```

#### Admin login
```ch
email: admin@example.com
password: 11111111
```

#### User login
```ch
email: user1@example.com
password: 11111111
OR
email: user2@example.com
password: 11111111
```

#### For Send Mail using mailtrap
```ch
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=your_mail_PORT
MAIL_USERNAME=your_mail_USERNAME
MAIL_PASSWORD=your_mail_PASSWORD
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
