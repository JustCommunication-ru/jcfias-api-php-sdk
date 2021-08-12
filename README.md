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
    'assumptions' => false, // ответ с результатами, которые не были найдены в ФИАС
    'autocomplete' => false // искаться результаты не по точному совпадению, а со *
];

/** @var SearchResponse $response */
$response = $client->sendSearchRequest(new SearchRequest('владивосток', ['assumptions' => true]));
```

### Поиск по строке (batch)

```php
$defaultParams = [
    'assumptions' => false, // ответ с результатами, которые не были найдены в ФИАС
];

/** @var SearchBatchResponse $response */
$response = $client->sendSearchBatchRequest(new SearchBatchRequest(['владивосток', 'москва братиславская']));
```

### Список регионов

```php
/** @var RegionsResponse $response */
$response = $client->sendRegionsRequest(new RegionsRequest());
```

### Список городов

```php
/** @var TownsResponse $response */
$response = $client->sendTownsRequest(new TownsRequest());
```
