![logo](./public_html/images/img_navbar_logo.png)

## Deployment Instructions

```
// update the code to latest master branch
git pull origin master

// create a local php alias to the correct version
alias php=/opt/cpanel/ea-php80/root/usr/bin/php

// install with composer
/opt/cpanel/ea-php80/root/usr/bin/php /opt/cpanel/composer/bin/composer install --no-dev
```

## Deployment Server Initial Setup Instructions

```
// copy the .env file
cp .env.example .env

// generate app key
php artisan key:generate

// edit the .env file with the server settings
```

#### update additionally below variables in .env

```
-------for Send Mail - use sengrid -------

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERAPP_NAME="HOT POP"key
MAIL_PASSWORD={key}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS =
MAIL_FROM_NAME =

------- for realtime communication -------
BROADCAST_DRIVER=pusher

PUSHER_APP_ID={ID}
PUSHER_APP_KEY={key}
PUSHER_APP_SECRET={secret}
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER={cluster}


-------for Audio and Video Call-------

TWILIO_ACCOUNT_SID =
TWILIO_API_KEY=
TWILIO_API_SECRET=
TWILIO_SID=

-------for Social Login-------

TIKTOK_CLIENT_ID=
TIKTOK_CLIENT_SECRET=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=

INSTAGRAM_CLIENT_ID=
INSTAGRAM_CLIENT_SECRET=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=

-------mail for contact us mail received-------
WEBMASTER_EMAIL={mail}

-------for check inactive time to set offline state-------
SESSION_TIME_INACTIVE={minutes}

-------for give free access -  first users count-------
FREE_USERS_LIMIT={500 default}

-------for search Location-------
GOOGLE_MAP_API_KEY={API Key}

-------for sentry-------
SENTRY_LARAVEL_DSN={sentry Key}
```

```
// link the storage folder
php artisan storage:link

// install dusk (if you're going to run Browser Tests)
composer require --dev laravel/dusk
php artisan dusk:install
```

```

// update server crontab file

crontab -e

and put

* * * * * cd [add project folder path] && php artisan schedule:run >> /dev/null 2>&1


```

### Development Instructions

Migrate and seed the database

```
php artisan db:refresh
```

Run the local development watcher

```
npm run dev
```

Generate API documentation

```
// to auto generate docs and API tests.
// WARNING: This will overwrite existing API Tests
php artisan generate:docs-tests

// to only generate the docs
php artisan generate:docs
```

Run PHPUnit Tests

```
./vendor/bin/phpunit
```

Run Dusk Tests

```
php artisan dusk --stop-on-error --stop-on-failure
```

Before releasing to production, compile the assets

```
npm run build
```

## Licence

Project Licenced to HOT POP. [Copyright Elegant Media](https://www.elegantmedia.com.au)

## Copyright

Copyright (c) Elegant Media.
