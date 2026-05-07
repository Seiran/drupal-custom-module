# My Custom Module для Drupal

Модуль для тестового завдання, що демонструє створення сторінки, форми з AJAX, блоку та кастомного сервісу

## Вимоги

- Drupal 10
- PHP 8.0 або вище
- Composer
- MySQL/MariaDB

## Встановлення проєкту

### 1. Встановлення Drupal через Composer

```bash
# Створення проєкту
composer create-project drupal/recommended-project:^10 drupal-project
cd drupal-project

## Налаштування бази даних

Створіть базу даних MySQL:

```sql
CREATE DATABASE drupal10_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

---

## Встановлення Drupal

### Через браузер

1. Перейдіть на `http://localhost/drupal-project/web`
2. Дотримуйтесь інструкцій інсталятора

### Через Drush

```bash
vendor/bin/drush site:install standard \
  --db-url=mysql://root:root@localhost/drupal10_db \
  --site-name="My Drupal Site" \
  --account-name=admin \
  --account-pass=admin123
```

---

## Встановлення модуля

### 1. Копіювання модуля

Скопіюйте папку `my_custom_module` до:

```
web/modules/custom/
```

### 2. Увімкнення модуля

**Через адмінку:**

1. Зайдіть в Administration → Extend (`/admin/modules`)
2. Знайдіть **"My Custom Module"**
3. Поставте галочку та натисніть **"Install"**

**Через Drush:**

```bash
vendor/bin/drush en my_custom_module -y
vendor/bin/drush cr
```

---

## Файлова структура модуля

```
my_custom_module/
├── my_custom_module.info.yml          # Опис модуля
├── my_custom_module.routing.yml       # Маршрути
├── my_custom_module.services.yml      # Реєстрація сервісу
├── my_custom_module.links.menu.yml    # Посилання в меню
├── README.md                          # Документація
└── src/
    ├── Controller/
    │   ├── FirstPageController.php    # Сторінка /my-first-page
    │   └── ServiceTestController.php  # Тестування сервісу
    ├── Form/
    │   └── SimpleForm.php             # Форма з AJAX
    ├── Plugin/
    │   └── Block/
    │       └── CurrentRouteBlock.php  # Блок з поточним маршрутом
    └── Service/
        └── EntityInfoService.php      # Сервіс для роботи з entity
```

---

## Що реалізовано

### ✅ 1. Сторінка `/my-first-page`

- Виводить "Hello, Drupal!" и текущую дату/время
- Формат дати: `24.08.2022 14:22`
- Повністю вимкнено кешування
- Доступ: `http://your-site.com/my-first-page`

### ✅ 2. Проста форма з AJAX

- Текстове поле та кнопка "Відправити"
- Результат виводиться під формою: `Результат: %введений текст%`
- AJAX-відправлення без перезавантаження сторінки
- Обробка порожнього поля
- Доступ: `http://your-site.com/my-form-page`

### ✅ 3. Блок з поточним маршрутом

- Показує назву поточного маршруту (route name)
- Приклад: `Current route: entity.node.canonical`
- Правильне кешування з контекстом `route`
- Коректно працює на різних сторінках
- Назва блоку: **"Current Route Block"**

### ✅ 4. Кастомний сервіс

- Сервіс: `my_custom_module.entity_info_service`
- Використовує `entity_type.manager`
- Метод: `getEntityInfo($entity_type, $entity_id)`
- Повертає базову інформацію про сутність:
  - `label` — назва
  - `bundle` — тип
  - `created` / `changed` — дати
  - додаткова інформація для `node` / `user`
- Тестова сторінка: `/test-service/node/1`

---

## Використання

### Доступні сторінки

| URL | Опис |
|-----|----------|
| `/my-first-page` | Сторінка з датою та часом |
| `/my-form-page` | Форма з AJAX |
| `/test-service/node/1` | Тестування сервісу |

### Розміщення блоку

1. Перейдіть в **Administration → Structure → Block layout**
2. Знайдіть блок **"Current Route Block"** (секція Custom)
3. Натисніть **"Place block"**
4. Виберіть будь-який регіон (наприклад, `Sidebar second`)
5. Збережіть
