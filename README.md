## Использование

```php
$client = new JcFIASClient('login', 'token');
```

## Методы

### Токен авторизации

```php
$token = JcFIASClient::getToken($login, $password);
```

### Поиск по строке

```php
$defaultParams = [
    'assumptions' => false, // дополнить ответ результатами, которые предположил скрипт, но которые не были найдены в ФИАС
    'autocomplete' => false // искаться результаты не по точному совпадению, а со *
];

$response = $client->sendSearchRequest(new SearchRequest('владивосток', ['assumptions' => true]));

### Список регионов

```php
$response = $client->sendRegionsRequest(new RegionsRequest());
```

### Список городов

```php
$response = $client->sendTownsRequest(new TownsRequest());
```
