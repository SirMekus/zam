# Zam

Zam helps to receive and process client request(s), return response to client with appropriate header for a more robust and easier development

>Please note that database operations have been moved to its own special repository/package. If you need support for database operation use [Ahia](https://github.com/SirMekus/ahia) instead.

For receiving request from client you will likely do something like:

```php
if(isset($_POST['email']) and isset($_POST['name']) and isset($_POST['password'])){
    //take input
}
else{
    // cancel operation or return warning
}
```

With this package you can simply do:

```php
require_once 'path_to_vendor/autoload.php';

$name = request(["name"=>"name");

$email = request(["name"=>"email");

$password = request(["name"=>"password");

//continue execution
```

## Installation

To get started all you need to do is:

```php
composer require sirmekus/zam
```

and you're in. Please note that if you use Laravel framework then this package may clash with it because there exists functions with similar names as in Laravel. Zam, in Igbo language, means **"Answer Me"** and it does this exactly. If you use Laravel then you may not need this package.

---

## Usage

---

## Receiving/Accepting Request(s)

To accept request you just need to pass an optional configuration array to the `request()` function as key-value pairs. **Only one key is important to be passed - the NAME key**. E.g:

```php
require_once 'path_to_vendor/autoload.php';

$name = request(["name"=>"name"]);

$email = request(["name"=>"email", "message"=>"Please provide your email address"]);

$password = request(["name"=>"password", "method"=>"post"]);
```

The possible configuration keys are:

- `name` : This is the name of the input/request coming from your front end.
- `required` : Boolean value that indicates whether the expected input must be present. Default is set to `true`.
- `method` : The expected method (HTTP verbs: GET, POST, PUT, etc) that the expected input must follow. Default is `POST`
- `message` : If present this will be sent back to the client if the `name` is not set or empty.
- `nullable` : If an expected input isn't set or is empty it tells us whether to proceed with request or throw error to client.

Another way to receive input from client is by calling the `request()` function without any argument then access the expected input as a dynamic property on it. Example:

```php
require_once 'path_to_vendor/autoload.php';

request()->name; 
```

>In the above example, if name is set in the form it'll return the value else it returns null.

Inputs are sanitized before being passed to your application. Note that this function can also sanitize arrays when passed to it. Validation should be done on client side including error checks.

Also, this package integrates well with [Zam](https://www.npmjs.com/package/mmuo) package when using AJAX for making request(s) from front end.

> Note that you can accept any request with any HTTP verb. However, for requests that don't use the GET, POST or PUT method we encourage you to pass the request in `"REQUEST PAYLOAD"` (JSON) format (instead of Formdata). Also, to verify that the request uses a particular HTTP verb you should check the `$_SERVER['REQUEST_METHOD']`

---

## Response(s)

This will typically be useful to users who use `axios` library or [Zam](https://www.npmjs.com/package/mmuo) package as the appropriate HTTP status header will be specified. E.g

```php
require_once 'path_to_vendor/autoload.php';

return response("Thank you for using Zam.");
```

You can pass an optional parameter as second argument to this function which is the HTTP status code to send to the client. By default a `200 HTTP status code` is sent to the client. Example:

```php
require_once 'path_to_vendor/autoload.php';

return response("There was an error in submission", 403);
```

>Note that you can also pass an array as argument to this function and it'll be converted to JSON before being sent to client.

The supported HTTP status code (and their meanings) you can pass and that can be sent to client are:

- 200=>ok,
- 201=>Created,
- 202=>Accepted,
- 204=>No Content,
- 301=>Moved Permanently,
- 308=>Permanent Redirect,
- 422=>Unprocessable Entity,
- 401=>unauthorized,
- 403=>forbidden,
- 404=>Not Found,
- 405=>Method Not Allowed,
- 500=>Internal Server Error,
- 503=Service Unavailable,
- 408=>Request Timeout,
- 411=>Length Required,
- 413=>Payload Too Large,
- 406=>Not Acceptable

**Only the code is needed to be passed.**

---

## Displaying Response to user(s)
This package exposes a function called `error()` which accepts the HTML field's name as argument and display any error related to that field as set by the server. E.g:

```php
//index.php
<?php
require_once 'vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="en">

...

<body>
    <form>
    ...
    <div>
    <label>Name</label>
    <input type="text" name="name" />
    <?php if(error('name')) echo error('name'); ?>
    </div>
    ...
    </form>

</body>

</html>
```

Now if there's an error for the `name` field above it will be displayed accordingly. You can do this for other fields as well by supplying the appropriate name.

---

## Meanwhile

 You can connect with me on [LinkedIn](https://www.linkedin.com/in/sirmekus) for insightful tips and so we can grow our networks together.

 Patronise us on [Webloit](https://www.webloit.com).

 And follow me on [Twitter](https://www.twitter.com/Sire_Mekus).

 I encourage contribution even if it's in the documentation. Thank you, and I really hope you find this package helpful.
