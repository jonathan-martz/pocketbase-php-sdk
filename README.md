### Php Sdk for Pocketbase

***
##### This Repository are in active Development
***

***
### Gitlab is the one for Packagist, but they should both be up to Date.
##### Github: https://github.com/jonathan-martz/pocketbase-php-sdk/
##### Gitlab: https://gitlab.jonathan-martz.de/softwareentwicklung/pocketbase-php-sdk
***

``` bash
composer require jonathan-martz/pocketbase-php-sdk
```

``` php
// Example init and use
use \Pb\Client as pb;
$pb = new pb('https://admin.pocketbase.dev');
var_dump($pb->collection('users')->getList());
```

***
#### Crud adapted from js-sdk to php

``` php
// Returns a paginated records list.
$pb->collection(collectionIdOrName)->getList(int $page = 1, int $perPage = 30, array $queryParams = []);

// Returns a list with all records batch fetched at once.
$pb->collection(collectionIdOrName)->getFullList(int $batch = 200, array $queryParams = []);

// Returns the first found record matching the specified filter.
$pb->collection(collectionIdOrName)->getFirstListItem(string $filter, array $queryParams = []);

// Returns a single record by its id.
$pb->collection(collectionIdOrName)->getOne(string $recordId, array $queryParams = []);

// Creates (aka. register) a new record.
$pb->collection(collectionIdOrName)->create(array  $bodyParams = [], array $queryParams = []);

// Updates an existing record by its id.
$pb->collection(collectionIdOrName)->update(string $recordId, array $bodyParams = [],array $queryParams = []);

// Deletes a single record by its id.
$pb->collection(collectionIdOrName)->delete(string $recordId, array $queryParams = []);

// Custom Logic
$pb->collection(collectionIdOrName)->upload(string $recordId, string $field, string $filepath);
```
