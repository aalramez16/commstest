# "Comms Test"
TODO: Rename this lol.

## What is This
This is an API for a collection of chatrooms. Meant to be pretty locked down in terms of access, mostly for friends and family.
If you come across this, feel free to fork it or whatever. No guarantees it'll work and I'm not responsible for how you use it.

## Dependencies
This is a PHP project. You'll need at least **PHP 8.2**
You will need to have [composer](https://getcomposer.org/) installed on your device. Composer is a dependency manager for PHP projects.
You will also need MySQL for db purposes (if you know what you're doing, I trust you can make this something else.)

Once you've cloned the repo, run this in your terminal:

```bash
composer install
```

This will install the dependencies specified in `composer.json`. This is very similar to how npm works.

## Architecture
- Slim Framework
- Doctrine ORM
- DTO mapping layer
- Token-based authentication middleware

## Local Environment
In order to run the project, you will need to create a LAMP (or WAMP) environment for hosting php on an apache server.
I chose LAMP because testing on shared hosting is the cheapest and easiest option.
It also pre-includes most of the above technologies so you can worry about getting right into it.

I use [XAMPP](https://www.apachefriends.org/) for my Windows setup. If you do the same, you'll want to clone this project into the htdocs folder.

> Side Note: If you’re curious about multi-site configuration on Windows with Apache, talk to me 🙂

## Configuration
If you choose to host locally, you’ll also need to create a `.config.php` file in your root directory. It should look something like this:
```PHP
<?php

return [
    // General
    'ROOT_DIR'          => 'C:/xampp/htdocs/commstest', // or wherever you put it
    'BASE_URL'          => 'http://localhost', // or whatever alias you end up using
    'IS_DEVELOPMENT'    => true,
    'TIME_ZONE'         => 'UTC',
    // DB
    'DB_HOST'           => 'localhost',
    'DB_NAME'           => 'commstest',
    'DB_PORT'           => 3306,
    'DB_USERNAME'       => 'root',
    'DB_PASSWORD'       => '' // use a password if you host it somewhere public
];
```

## DB Setup
You'll likely want to import the database for the schema and some initial data to work with. You can find that in `db/commstest.sql`
- If you're using PHPMyAdmin, you can import this file to create a new database
- If you're real hardcore, you can run this instead:

```bash
mysql -u root -p commstest < db/commstest.sql
```

## Running the Project
Get Apache running the project however you chose to configure it.
Done? Swag! When you hit localhost (or wherever it's hosted), you should see something like this:
```json
{
  "error": "Token required."
}
```

to get rid of this, you'll need to add the following to your request headers:

| Key | Value |
| --- | --- |
| X-Auth-Token | changeme |
> For the love of God, change this in your DB to something that won't get compromised.

Now, you should see the following:
```json
{
    "message": "Welcome!"
}
```

## Useful Endpoints
There are a few more than are shown here but these should help get you started.

### Rooms 
#### `GET /rooms` 
Retrieves all rooms in reverse chronological order.

Example response body:
```json
[
    {
        "id": 1,
        "name": "First Room",
        "createdAt": "2026-02-13T21:59:26+00:00"
    },
    {
        "id": 2,
        "name": "Second Room",
        "createdAt": "2026-02-13T21:00:06+00:00"
    },
]
```

#### `GET /room/1/messages` 
Retrieves all messages from a specified room in reverse chronological order.

Example response body:
```json
[
    {
        "id": 18,
        "senderName": "Test User 3",
        "messageContents": "Heyooo!",
        "sentAt": "2026-02-14T21:40:54+00:00"
    },
    {
        "id": 17,
        "senderName": "Test User 2",
        "messageContents": "Does this work?",
        "sentAt": "2026-02-14T18:15:44+00:00"
    },
    {
        "id": 2,
        "senderName": "Test User 1",
        "messageContents": "This is the second message.",
        "sentAt": "2026-02-11T16:55:00+00:00"
    },
    {
        "id": 1,
        "senderName": "Test User 1",
        "messageContents": "This is the first message.",
        "sentAt": "2026-02-11T16:54:00+00:00"
    }
]
```

### Messages
#### `POST /message` 

Creates a new message.

Example Request body:
```json
{
    "contents": "Hey what's going on guys?",
    "roomId": 1
}
```
Example response body:
```json
{
    "id": 18,
    "senderName": "Test User 1",
    "messageContents": "Hey what's going on guys?",
    "sentAt": "2026-02-14T21:40:54+00:00"
}
```

### Users
#### `GET /users` 

Gets all users

Example response body:
```json
[
    {
        "id": 5,
        "name": "Test User 5",
        "provisionerName": "Test User",
        "createdAt": "2026-02-14T17:26:00+00:00"
    },
    {
        "id": 4,
        "name": "Test User 4",
        "provisionerName": "Test User",
        "createdAt": "2026-02-14T17:25:55+00:00"
    },
    {
        "id": 3,
        "name": "Test User 3",
        "provisionerName": "Test User",
        "createdAt": "2026-02-14T17:25:47+00:00"
    },
    {
        "id": 2,
        "name": "Test User 2",
        "provisionerName": "Test User",
        "createdAt": "2026-02-14T17:25:32+00:00"
    },
    {
        "id": 1,
        "name": "Test User 1",
        "provisionerName": "Self-provisioned",
        "createdAt": "2026-02-12T09:24:58+00:00"
    }
]
```
