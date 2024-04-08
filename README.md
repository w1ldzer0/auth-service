# Auth service

## Описание
Используется для задач авторизации пользователей:
* получение и обновление JWT токена
* регистрация и авторизация пользователя
* изменение и восстановление пароля пользователя
* подтверждение email пользователя

## Swagger документация
http://{your_host}/api/auth/doc

## Запуск на dev
```
git clone git@alfaleads.gitlab.yandexcloud.net:platforma/auth-service.git
cd auth-service
git checkout dev
make up
```

## Связи
* [User Service](https://alfaleads.gitlab.yandexcloud.net/platforma/user-service/-/blob/dev/README.md) (required)
* [Notification Service](https://alfaleads.gitlab.yandexcloud.net/platforma/notification-service/-/blob/dev/README.md)

