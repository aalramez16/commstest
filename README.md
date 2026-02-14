# "Comms Test"
TODO: Rename this lol.

## What is This
This is an API for a collection of chatrooms. Meant to be pretty locked down in terms of access, mostly for friends and family.
If you come across this, feel free to fork it or whatever. No guarantees it'll work and I'm not responsible for how you use it.

## Dependencies
This is a PHP project, so you will need to have [composer](https://getcomposer.org/) installed on your device.

You can go to the root directory of this project and run `composer install` in order to install the dependencies specified in `composer.json`. This is very similar to how npm works.

## Local Environment
In order to run the project, you will need to create a LAMP (or WAMP) environment for hosting php on an apache server. I chose LAMP because testing on shared hosting is the cheapest and easiest option.

I use [XAMPP](https://www.apachefriends.org/) for my Windows setup. If you do the same, you'll want to clone this project into the htdocs folder.

> Side Note: If you’re curious about multi-site configuration on Windows with Apache, talk to me 🙂

## Environment Variables
If you choose to host locally, you’ll also need to create a `.config.php` file. This is where access to the following are controlled:
- Root Directory (where you cloned it)
- Base Url (localhost unless configured otherwise)
- Is Development (boolean)
- Time Zone (I would keep this as UTC, you can offset on a frontend app)
- Database Configuration Fields
