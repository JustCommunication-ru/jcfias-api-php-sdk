# JcFias API PHP SDK

[![Latest Stable Version](https://poser.pugx.org/justcommunication-ru/jcfias-api-php-sdk/v)](//packagist.org/packages/justcommunication-ru/jcfias-api-php-sdk) 
[![Total Downloads](https://poser.pugx.org/justcommunication-ru/jcfias-api-php-sdk/downloads)](//packagist.org/packages/justcommunication-ru/jcfias-api-php-sdk) 

PHP SDK для JcFias API

## Установка

`composer require justcommunication-ru/jcfias-api-php-sdk`

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
    'autocomplete' => false, // искаться результаты не по точному совпадению, а со *
    'house_number_not_exact' => false
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
$response = $client->sendSearchBatchRequest(new SearchBatchRequest(['владивосток', 'москва минская']));
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

***
***
***

#### Для использования API без данного SDK необходимо генерировать X-WSSE заголовок самостоятельно

Алгоритм на PHP

```php
function generateWsseHeader($username, $token): string
{
    $nonce = hash('sha512', uniqid(true));
    $created = date('c');
    $digest = base64_encode(sha1(base64_decode($nonce) . $created . $token, true));

    return sprintf('UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
        $username,
        $digest,
        $nonce,
        $created
    );
}
```

Алгоритм на JAVA

```java
package ru.wiweb.billing.service;

import org.springframework.util.Base64Utils;

import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.text.SimpleDateFormat;
import java.time.Duration;
import java.time.LocalDateTime;
import java.util.Date;
import java.util.Random;
import java.util.UUID;

/**
 * Утилита для генерации заголовка X-WSSE
 */
public class WsseHeaderGenerator {

    public static SimpleDateFormat isoDateFormatter = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ssXXX");

    /**
     * Вызвать этот метод для того, чтобы получить подходящее содержимое заголовка
     * @param username имя пользователя
     * @param token токен пользователя
     * @return
     * @throws NoSuchAlgorithmException
     */
    public static String getWSSEHeader(String username, String token) throws NoSuchAlgorithmException {
        String noncePlain = getUUID();
        String nonce = Base64Utils.encodeToString(noncePlain.getBytes(StandardCharsets.UTF_8));
        String created = isoDateFormatter.format(new Date());
        String digest = Base64Utils.encodeToString(sha1(noncePlain + created + token));
        StringBuilder builder = new StringBuilder();
        builder.append("UsernameToken Username=\"").append(username).append("\", ")
                .append("PasswordDigest=\"").append(digest).append("\", ")
                .append("Nonce=\"").append(nonce).append("\", ")
                .append("Created=\"").append(created).append("\"");
        return builder.toString();
    }

    private static String getUUID() {
        long most64SigBits = get64MostSignificantBitsForVersion1();
        long least64SigBits = get64LeastSignificantBitsForVersion1();
        return new UUID(most64SigBits, least64SigBits).toString();
    }

    private static long get64LeastSignificantBitsForVersion1() {
        Random random = new Random();
        long random63BitLong = random.nextLong() & 0x3FFFFFFFFFFFFFFFL;
        long variant3BitFlag = 0x8000000000000000L;
        return random63BitLong + variant3BitFlag;
    }

    private static long get64MostSignificantBitsForVersion1() {
        LocalDateTime start = LocalDateTime.of(1230, 11, 12, 2, 12, 58);
        Duration duration = Duration.between(start, LocalDateTime.now());
        long seconds = duration.getSeconds();
        long nanos = duration.getNano();
        long timeForUuidIn100Nanos = seconds * 10000000 + nanos * 100;
        long least12SignificatBitOfTime = (timeForUuidIn100Nanos & 0x000000000000FFFFL) >> 4;
        long version = 1 << 12;
        return (timeForUuidIn100Nanos & 0xFFFFFFFFFFFF0000L) + version + least12SignificatBitOfTime;
    }

    public static byte[] sha1(String input) throws NoSuchAlgorithmException {
        MessageDigest mDigest = MessageDigest.getInstance("SHA1");
        return mDigest.digest(input.getBytes());
    }
}
```
