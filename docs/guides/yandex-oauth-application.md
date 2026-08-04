# Регистрация приложения в Яндекс OAuth

[← Вернуться к README](../../README.md)

## Создание приложения

1. Откройте [Яндекс OAuth](https://oauth.yandex.ru/).
2. Создайте приложение **для авторизации пользователей**.
3. Выберите платформу **Веб-сервисы**.
4. Добавьте Redirect URI, на который Yandex вернёт пользователя после авторизации.

Пример:

```text
https://example.com/oauth/yandex/callback
```

Scheme, host, port и path должны совпадать с callback вашего приложения.

## Права доступа

Выбирайте только те права, которые действительно использует приложение.

| Право | Данные |
|---|---|
| `login:info` | логин, имя, фамилия и пол |
| `login:email` | адрес электронной почты |
| `login:avatar` | портрет пользователя |
| `login:birthday` | дата рождения |
| `login:default_phone` | номер телефона |

При формировании authorization URL можно передать:

- `scope` — права, необходимые приложению;
- `optional_scope` — дополнительные права, от которых пользователь может отказаться.

Если `scope` и `optional_scope` не переданы, Yandex выдаёт токен с правами,
выбранными в настройках приложения.

## Реквизиты приложения

После создания приложения Yandex выдаст:

```text
Client ID
Client Secret
```

Храните их в конфигурации или переменных окружения. Не добавляйте `Client Secret`
в Git-репозиторий, открытые конфигурационные файлы, логи и сообщения об ошибках.

Пример переменных окружения:

```dotenv
YANDEX_CLIENT_ID=your-client-id
YANDEX_CLIENT_SECRET=your-client-secret
YANDEX_REDIRECT_URI=https://example.com/oauth/yandex/callback
```

## Callback

При успешной авторизации Yandex вернёт:

```text
?code=<authorization-code>&state=<state>
```

При отказе или ошибке:

```text
?error=<error-code>&error_description=<description>&state=<state>
```

Проверяйте `state` в обоих сценариях до дальнейшей обработки callback.

## Официальная документация

- [Регистрация приложения](https://yandex.com/dev/id/doc/ru/register-client)
- [Получение кода из URL](https://yandex.com/dev/id/doc/ru/codes/code-url)
- [Получение данных пользователя](https://yandex.com/dev/id/doc/ru/user-information)
