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
    'town' => '', // название города по ФИАС (используется вместо aoguids, не учитывается при version = 2)
    'region' => '', // название региона по ФИАС (используется вместо aoguids, не учитывается при version = 2)
    'aoguids' => [], // массив aoguid по базе ФИАС для ускорения поиска (не учитывается при version = 2)
    'version' => 1, // версия алгортма поиска, 1 и 2
    'assumptions' => false, // дополнить ответ результатами, которые предположил скрипт, но которые не были найдены в ФИАС
    'autocomplete' => false // искаться результаты не по точному совпадению, а со *
];

$response = $client->sendSearchRequest(new SearchRequest('владивосток', ['aoguids' => ['43909681-d6e1-432d-b61f-ddac393cb5da']]));
```
Варианты одного и того же поискового запроса. Первый вариант самый долгий. Последний вариант самый быстрый.

```php
'/api/search?string=владивосток+калинина'
'/api/search?string=калинина&town=владивосток'
'/api/search?string=калинина&aoguids[]=7b6de6a5-86d0-4735-b11a-499081111af8'
```

### Список регионов

```php
$response = $client->sendRegionsRequest(new RegionsRequest());
```

### Список городов

```php
$response = $client->sendTownsRequest(new TownsRequest());
```
