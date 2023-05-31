# BeycanPress\Request - 0.1.0 #

It is a simple and useful class for receiving data in many different request types and sending requests.

## Features ##

Features included :

- Retrieve data from standard get and post parameters.
- Get the data in the request body
- Easily parse and get the data in the request body with methods such as json, xml, etc.
- With the "all" method, get all the parameters sent regardless of type.
- Get request header information with "titles" method
- PSR-4 compliant ([autoloader](https://www.php-fig.org/psr/psr-4/))

## Documentation ##

## Installation

### Using Composer

* Obtain [Composer](https://getcomposer.org)
* Run `composer require beycanpress/request`

### Use alternate file load

In case you can't use Composer, you can include `Request.php` into your project.

`require_once __DIR__ . '/src/Request.php';`

Afterwards you can use `Request` class.

### Sample usage
If you have included the class in your project with composer autoload or manually.

You can start it as follows, now you can do all your request jobs with the "$request" variable.
```
use BeycanPress\Request;
$request = new Request;
$request->all();
```

or

```
use BeycanPress\Request;
Request::init()->all();
```
You can use static "init" method as above.

### Methods
<details>
  <summary>post</summary>
  With this method, you can easily capture the data sent with the "post" method.
  <br><br>

  Sample:
  ```
  $result = $request->post(); // Result: all data
  $result = $request->post('name'); // Result: only value with "name" key
  ```
</details>

<details>
  <summary>get</summary>
  With this method, you can easily capture the data sent with the "get" method.
  <br><br>

  Sample:
  ```
  $result = $request->get(); // Result: all data
  $result = $request->get('name'); // Result: only value with "name" key
  ```
</details>

<details>
  <summary>files</summary>
  You can use it to get the files you send, that is, the files that come to the "$_FILES" variable.
  <br>
  NOTE: This method will be extended to upload files easily in the future.
  <br><br>

  Sample:
  ```
  $result = $request->files(); // Result: all files
  $result = $request->files('profile-picture'); // Result: only file with "profile-picture" key
  ```
</details>

<details>
  <summary>headers</summary>
  Allows you to receive request headers.
  <br><br>

  Sample:
  ```
  $result = $request->headers(); // Result: all headers data
  $result = $request->headers('X-Auth'); // Result: only value with "X-Auth" key
  ```
</details>

<details>
  <summary>json</summary>
  If the data in the request body is json data, it is parsed and kept in the class, you can easily capture it with this method.
  <br><br>

  Sample:
  ```
  $result = $request->json(); // Result: all data
  $result = $request->json('name'); // Result: only value with "name" key
  ```
</details>

<details>
  <summary>xml</summary>
  If the data in the request body is xml data, it is parsed and kept in the class, you can easily capture it with this method.
  <br><br>

  Sample:
  ```
  $result = $request->xml(); // Result: all data
  $result = $request->xml('name'); // Result: only value with "name" key
  ```
</details>

<details>
  <summary>getContent</summary>
  It allows you to get the data in the request body.
  <br><br>

  Sample:
  ```
  $result = $request->getContent(); // Result: data in the request body.
  ```
</details>

<details>
  <summary>all</summary>
  With this method, you can easily capture the request type and data type (if it is recognized and parsed).
  <br><br>

  Sample:
  ```
  $result = $request->all(); // Result: all data
  $result = $request->all('name'); // Result: only value with "name" key
  ```
</details>

<details>
  <summary>init</summary>
  You can use it to access the class as static.
  <br><br>

  Sample:
  ```
  $result = Request::init()->all();
  ```
</details>

<details>
  <summary>getMethod</summary>
  You can use it to get the request type.
  <br><br>

  Sample:
  ```
  $result = $request->getMethod(); // Result: request method name
  ```
</details>

<details>
  <summary>getErrors</summary>
  If you get an error while running the class, you can use this method to show errors.
  <br><br>

  Sample:
  ```
  $result = $request->getErrors(); // Result: If an error is caught, you will see them.
  ```
</details>


## License ##

This library is under the [MIT](https://github.com/BeycanPress/request/blob/main/LICENSE).
