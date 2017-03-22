--
-- Структура таблицы `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор действия',
  `name` varchar(20) NOT NULL COMMENT 'Системное имя действия',
  `title` varchar(50) NOT NULL COMMENT 'Заголовок действия',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Дамп данных таблицы `actions`
--

INSERT INTO `actions` (`id`, `name`, `title`) VALUES
(1, 'panel', 'Панель управления'),
(2, 'panel-settings', 'Настройки сайта'),
(3, 'change-group', 'Право менять группу допуска'),
(4, 'change-modules', 'Управление модулями'),
(5, 'menu-editor', 'Редактирование меню');

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `mother` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Родительская папка',
  `name` varchar(50) NOT NULL COMMENT 'Имя настройки',
  `title` varchar(100) NOT NULL COMMENT 'Название настройки',
  `type` set('text','int','checkbox') NOT NULL DEFAULT 'text' COMMENT 'Тип настройки',
  `value` varchar(100) NOT NULL COMMENT 'значение',
  `group` varchar(30) NOT NULL COMMENT 'Доступ к изменению настройки',
  `autoload` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`mother`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Конфигурация системы';

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`mother`, `name`, `title`, `type`, `value`, `group`, `autoload`) VALUES
('0', 'developer', 'Режим разработчика', 'text', 'directory', 'root', '1'),
('0', 'reg', 'Регистрация', 'text', 'directory', '', '1'),
('developer', 'memcache_table', 'Таблица Memcache', 'checkbox', '0', '', '1'),
('developer', 'params_table', 'Вывод контроллера и параметров', 'checkbox', '0', '', '1'),
('developer', 'sql_table', 'Таблица SQL запросов', 'checkbox', '0', '', '1'),
('developer', 'tpl_borders', 'Отображать границы шаблонов в html комментариях', 'checkbox', '0', '', '1'),
('developer', 'show_gen_stat', 'Статистика генерации страницы', 'checkbox', '1', 'root', '1'),
('reg', 'captcha', 'Captcha', 'checkbox', '1', '', '0'),
('reg', 'email', 'Поле email при регистрации', 'checkbox', '1', '', '0'),
('reg', 'email_must', 'Подтверждение email', 'checkbox', '1', '', '0'),
('reg', 'on', 'Регистрация включена', 'checkbox', '1', '', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `name` varchar(20) NOT NULL COMMENT 'Системное имя',
  `title` varchar(50) NOT NULL COMMENT 'Название группы',
  `actions` varchar(200) NOT NULL COMMENT 'Разрешённые действия',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`name`, `title`, `actions`) VALUES
('root', 'Супер админ', ''),
('admin', 'Админ', '1,2,5'),
('user', 'Пользователь', '');

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `mother` varchar(30) NOT NULL DEFAULT '0' COMMENT 'Материнский пункт',
  `name` varchar(30) NOT NULL COMMENT 'Системное имя',
  `title` varchar(100) NOT NULL COMMENT 'Заголовок',
  `pos` int(11) NOT NULL DEFAULT '0' COMMENT 'Позиция',
  `access` varchar(100) NOT NULL COMMENT 'Правила доступа',
  `url` varchar(255) NOT NULL COMMENT 'Ссылка',
  UNIQUE KEY `name` (`name`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`mother`, `name`, `title`, `pos`, `access`, `url`) VALUES
('0', 'main', 'Главная', 0, '', '/'),
('0', 'anketa', 'Анкета', 1, 'user', '/user/{user->id}'),
('0', 'panel', 'Админка', 2, 'panel', '/panel'),
('panel', 'settings', 'Настройки сайта', 0, 'root', '/panel/settings'),
('panel', 'access', 'Настройки доступа', 1, 'root', '/panel/access'),
('panel', 'modules', 'Модули', 4, 'root', '/panel/modules'),
('panel', 'users', 'Пользователи', 3, 'panel', '/panel/users'),
('panel', 'menu_editor', 'Редактор меню', 2, 'root', '/panel/menu'),
('modules', 'modules-add', 'Добавить', 1, 'change-modules', '/panel/modules/install'),
('settings', 'settings-developer', 'Разработчику', 0, 'root', '/panel/settings/developer'),
('settings', 'settings-reg', 'Регистрация', 1, 'root', '/panel/settings/reg');

-- --------------------------------------------------------

--
-- Структура таблицы `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Структура таблицы `modules_files`
--

CREATE TABLE IF NOT EXISTS `modules_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` int(11) NOT NULL,
  `fname` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tmp_users`
--

CREATE TABLE IF NOT EXISTS `tmp_users` (
  `login` varchar(50) NOT NULL COMMENT 'Уникальный логин',
  `pas` varchar(32) NOT NULL COMMENT 'Хэш пароля',
  `name` VARCHAR(30) NOT NULL COMMENT 'Отображаемое имя' 
  `email` varchar(70) NOT NULL COMMENT 'Email пользователя',
  `code` varchar(50) NOT NULL COMMENT 'Уникальный код',
  `time` int(11) NOT NULL COMMENT 'Время создания записи',
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `login` (`login`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `login` varchar(50) NOT NULL COMMENT 'Уникальный логин',
  `pas` varchar(32) NOT NULL COMMENT 'Хэш пароля',
  `group` varchar(20) NOT NULL DEFAULT 'user' COMMENT 'Группа доступа',
  `email` varchar(70) NOT NULL COMMENT 'Email пользователя',
  `reg_time` int(11) NOT NULL COMMENT 'Время регистрации',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT 'Последняя активность',
  `online_time` int(11) NOT NULL DEFAULT '0' COMMENT 'Суммарное время пребывания на сайте',
  `ip` VARCHAR(15) COMMENT 'IP адресс последнего посещения',
  `ua` VARCHAR(150) COMMENT 'UserAgent последнего посещения'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`(20)),
  ADD KEY `email` (`email`(15)) USING BTREE;
--
-- Дамп данных таблицы `users`
--login - admin, pas - admin
INSERT INTO `users` (`id`, `login`, `pas`, `name`, `group`, `email`, `reg_time`) VALUES
(1, 'admin', '18951848aa49a788c8d84f9263c7339c', 'Администратор','root', 'test@mail.ru', 1355594448);

--
-- Структура таблицы `guests`
--
CREATE TABLE IF NOT EXISTS `guests` (
  `ip` varchar(15) NOT NULL,
  `ua` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `ref` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Неавторизированные гости сайта';
