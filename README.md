## Bookkeeper

A PHP/MySQL web app for keeping track of reading goals. Written by [Ben Crowder](http://bencrowder.net/) and [Chad Hansen](http://chadgh.com/).

### Installation

1. Copy `config.template.php` to `config.php`.
2. Edit `config.php` for your system. We'll refer to your site URL as `http://yoursite.com` from here on.
3. If you haven't already created a database in MySQL and a user for it, do that.
4. In your browser, go to http://yoursite.com/setup.
5. Copy the SQL statements on that page and execute them on your database.
6. Go back to http://yoursite.com/setup and give Google permission to give Bookkeeper information.
7. Enter a username and timezone and click Save.

New users can just go to http://yoursite.com/ and they'll be able to create a new account.

### Usage

Click on the `+ ADD BOOK` link and fill out the form. Goal dates can be left blank if you don't have a specific date in mind but just want to keep track of your reading. Reading days are the days that'll be counted when we calculate how many days are left.

Once you have a book created, you add entries (the box in the upper right) to tell Bookkeeper which page you're on. That's about it.

Note: For books with goal dates, the chart will only show reading days, so if it looks like there are some days missing, that's why.
