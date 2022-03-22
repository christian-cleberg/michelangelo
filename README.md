# Michelangelo - A Tumblr Web Client
A quick and easy web client for Tumblr written in PHP to display pictures and videos in a pleasing gallery.

## Installation
1. To create a Tumblr application and get your consumer key/secret pair, [create an app on the Tumblr site](https://www.tumblr.com/oauth/apps).
2. Set your environment variables in an `.htaccess` file. For example,
```
# Environment Variables
SetEnv CONSUMER_KEY "<your-consumer-key>"
SetEnv CONSUMER_SECRET "<your-consumer-secret>"
```
3. Add logic to your `.htaccess` file to ensure all URLs are processed by `index.php`.
```
# Handle all paths and pages
# FallbackResource /index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```
4. Git clone this repository into the web directory where you want the client to be served.
5. Done! You (and your users) can now login. The site will automatically redirect you to the Tumblr login page if you are not already authenticated.

### Gallery
![](https://img.cleberg.io/michelangelo/gallery.png)
