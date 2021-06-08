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
    'autocomplete' => false, // искаться результаты не по точному совпадению, а со *
    'aoguids' => [] // массив aoguid по базе ФИАС для ускорения поиска
];

$response = $client->sendSearchRequest(new SearchRequest('владивосток', ['aoguids' => ['43909681-d6e1-432d-b61f-ddac393cb5da']]));
```

### Список регионов

```php
$response = $client->sendRegionsRequest(new RegionsRequest());
```

### Список городов

```php
$response = $client->sendTownsRequest(new TownsRequest());
```
