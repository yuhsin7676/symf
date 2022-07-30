# symf

Данный проект представляет собой соцсеть типа Хабра. Позволяет делать следующее:
- Создавать посты
- Просматривать любые посты, написанными другими пользователями
- Комментировать их
- Создавать пользователей

Версия Symfony: 6.1;
Проверялась на php версии: 8.1

Инструкция по установке и настройке:
https://symfony.com/doc/current/setup.html

## Как использовать?
- /home - главная страница, на которой можно просматривать посты и оставлять комментарии. С нее же можно перейти на следующие страницы:
- /new_post - Здесь можно написать пост
- /new_user - Здесь можно создать нового пользователя

/faker - при переходе сюда система автоматически создаст в БД 100 новых пользователей, 10 постов и 300 комментариев к ним.

## Задача со звездочкой
1) Подсчет комментариев, непрочитанных с момента последнгего визита можно сделать по временным меткам:
вести подсчет всех комментариев, оставленных после ухода пользователя. Сложность O(n);
2) Если же комментарии неудаляемы, то можно запомнить количество всех комментариев на момент ухода пользователя,
количество всех комментариев на момент возвращения пользователя и посчитать разность. Сложность O(1);
