Installation:
----------------

LARAVEL 5
________________
________________

1. Add this: '"tzookb/tbmsg": "2"' to your composer.json file
2. run: "composer update tzookb/tbmsg"
3. Now add the service provider to your app.php file: "'Tzookb\TBMsg\TBMsgServiceProvider'"
4. It would be best if you register the Facade in your app.php file: "'TBMsg' => 'Tzookb\TBMsg\Facade\TBMsg'"
5. now you would need to publish the config and migrations with
    "php artisan vendor:publish --provider="Tzookb\TBMsg\TBMsgServiceProvider""
6. If you would like to publish only config or only migration files, simply add
    ""--tag=config" or ""--tag=migrations" accordingly
7. now you can add prefix to the created tables, go to your migrations folder and find the package migrations:
create_conv_users.php, create_conversations.php, create_messages.php, create_messages_status.php
and not add prefix to table name, and of course set the prefix in the package config



LARAVEL 4
________________
________________

** you must choose to use the older version of 1.1.* in your composer.json

1. Add this: '"tzookb/tbmsg": "1.1.*"' to your composer.json file
2. run: "composer update tzookb/tbmsg"
3. Now add the service provider to your app.php file: "'Tzookb\TBMsg\TBMsgServiceProvider'"
4. It would be best if you register the Facade in your app.php file: "'TBMsg' => 'Tzookb\TBMsg\Facade\TBMsg'"
5. publish the package configs "php artisan config:publish tzookb/tbmsg"
6. publish the package migrations:  "php artisan migrate:publish tzookb/tbmsg"
7. now you can add prefix to the created tables, go to your migrations folder and find the package migrations:
create_conv_users.php, create_conversations.php, create_messages.php, create_messages_status.php
and not add prefix to table name, and of course set the prefix in the package config