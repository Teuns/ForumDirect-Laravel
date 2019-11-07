# ForumDirect

After 6 months I finally managed to develop a - in my opinion - solid forum system that only has the basic functionality and can be used in production for everyone. That's why I release the source code now with support.

A demo can be accessed at [https://forums.teunstrik.com](https://forums.teunstrik.com). Feel free to test.

## How do I set it up?

* `Clone the code`
* `Navigate to the working directory`
* `Run composer install`
* `Rename .env.example and change the settings (i.e. DB, URL, etc)`
* `Run php artisan migrate --force`
* `Run php artisan db:seed`

### And then what?

Then it worked. If you get forbidden errors or something like that, you'll probably have to check if your webserver is set up correctly for Laravel apps.

#### Screenshots
![](https://blazor.nl/uploads/get/8c8143e7610024c7/Screenshot-2019-09-11-ForumDirect-Home-1)
(more to come)

#### License
ForumDirect is licensed under the MIT license.