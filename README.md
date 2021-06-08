## Использование

```php
$client = new JcFIASClient('login', 'token');
```

## Методы

### Токен авторизации

```php
$token = JcFIASClient::getToken($login, $password);
```

### Поиск

```php
// дополнит ответ результатами, которые предположил скрипт, но которые не были найдены в ФИАС
$params['assumptions'] = true;

// результыты будут искаться не по точному совпадению, а со *
$params['autocomplete'] = true;

// массив aoguid по базе ФИАС для ускорения поиска
$params['aoguids'] = ['7b6de6a5-86d0-4735-b11a-499081111af8'];

$response = $client->sendSearchRequest(new SearchRequest('владивосток', $params));
```
