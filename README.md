# X

1. $ composer install
2. $ php artisan migrate:fresh --seed
3. $ php artisan passport:install
4. Run supervisor for queue worker and laravel-echo-server
5. Run chrome for pdf generation
    * `$ pm2 start chrome --interpreter none -- --headless --disable-gpu --disable-translate --disable-extensions --disable-background-networking --safebrowsing-disable-auto-update --disable-sync --metrics-recording-only --disable-default-apps --no-first-run --mute-audio --hide-scrollbars --remote-debugging-port=9222`
6. Run node server for running node scripts
    * `$ node node-server.js`
