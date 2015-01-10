Installation:
----------------

1. Add this: '"tzookb/tbmsg": "1.0.*"' to your composer.json file
2. run: "composer update tzookb/tbmsg"
3. Now add the service provider to your app.php file: "'Tzookb\TBMsg\TBMsgServiceProvider'"
4. It would be best if you register the Facade in your app.php file: "'TBMsg' => 'Tzookb\TBMsg\Facade\TBMsg'"
5. run the migrations:  "php artisan migrate --package=tzookb/tbmsg"