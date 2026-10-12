# Завантаження сайту CG Painting

## Як має виглядати сайт (орієнтир)

- **Шапка:** логотип CG Painting, меню (Diensten, Realisaties, Werkwijze, Reviews, Contact), кнопка «Gratis offerte», «Bel nu»; на мобільному — бургер і виїжджаюче меню.
- **Hero:** заголовок «Schilderwerken zonder stress. Strak resultaat, snel geregeld.», підзаголовок, дві кнопки (синя «Gratis offerte aanvragen →», біла «Bekijk realisaties»), зірки 4.8/5, «Antwoord binnen 24u», «Actief in Limburg», справа фото з бейджем «24u reactie».
- **Trust strip:** сірий фон, три колонки: Uw regio / Offerte / Kwaliteit.
- **Wat we doen:** три картки — Schilderwerken, Dunpleisteren, Behangen/Decor (іконки, текст, «Vraag prijs →»).
- **Waarom:** блок «Waarom kiezen voor CG Painting?» + before/after слайдер (waarom-voor.jpg, waarom-na.jpg).
- **Werkwijze:** 4 кроки.
- **Realisaties:** таби з проектами та галереями фото (project1…, project6…).
- **CTA-banner:** синій блок «Start uw project», кнопка «Gratis offerte aanvragen».
- **Reviews:** відгуки з зірками.
- **Contact:** форма (send.php), пункти з галочками, телефон 0478 78 48 33.
- **FAQ:** акордеон з питаннями.
- **Footer:** лого, Diensten, Werkgebieden, CG PAINTING (телефон, email, адреса, BTW), Valtex Digital, посилання на Privacybeleid та Algemene voorwaarden.

Усе це вже є в **index.html** (один файл з усіма стилями і розміткою). Щоб так само виглядало на хостингу — заливай саме цей набір файлів.

---

## Варіант 1: Залити просто на хостинг (рекомендовано)

Тоді сайт виглядатиме **точно як зараз** у вас локально: усі блоки, кнопки, стилі, форма, галереї, FAQ.

### Що саме залити

У **корінь сайту** (наприклад `public_html` на Hostinger) мають потрапити:

| Що залити | Опис |
|-----------|------|
| **index.html** | Головна сторінка (усі стилі всередині, один файл). |
| **send.php** | Обробка форми — відправка листа на hello@cgpainting.be. |
| **call.php** | Посилання «Bel klant» у листі (відкриває дзвінок). |
| **calendar-add.php** | Посилання «In agenda zetten» у листі. |
| **privacy.html** | Сторінка Privacybeleid. |
| **algemene-voorwaarden.html** | Сторінка Algemene voorwaarden. |
| **assets/** | Ціла папка з усіма файлами всередині (лого, hero-foto, проекти, waarom-voor/na). |

### Структура на хостингу (після заливки)

```
public_html/
  index.html
  send.php
  call.php
  calendar-add.php
  privacy.html
  algemene-voorwaarden.html
  assets/
    cg-painting-logo.png
    hero-foto.jpg
    waarom-voor.jpg
    waarom-na.jpg
    project1.jpeg
    project1.1.jpeg
    ... (усі project*.jpeg)
```

### Як залити

1. Відкрий у себе папку проєкту **CG PAINTING**.
2. Виділи тільки ці файли й папку:
   - index.html  
   - send.php  
   - call.php  
   - calendar-add.php  
   - privacy.html  
   - algemene-voorwaarden.html  
   - папка **assets** (разом з усім вмістом).
3. Запакуй їх у **один .zip** (назва будь-яка, наприклад `cgpainting-site.zip`).
4. У панелі Hostinger: **Файловий менеджер** → зайди в `public_html` → **Завантажити** → вибери цей zip → розархівуй у `public_html`.

Або через **FTP**: підключись до хостингу і скопіюй перелічені файли та папку `assets` у корінь сайту (ту саму папку, де буде index.html).

### Після заливки

- Головна сторінка: `https://ваш-домен.be/` або `https://ваш-домен.be/index.html`.
- Форма відправляється на **send.php**; листи йдуть на **hello@cgpainting.be** (якщо хостинг підтримує PHP mail або SMTP).
- Посилання в футері: **Privacybeleid** → privacy.html, **Algemene voorwaarden** → algemene-voorwaarden.html.

### Що не заливати

- Папку **local-dev/** (там Python для локального тесту).
- Папку **wordpress/** (потрібна тільки для варіанту з WordPress).
- Папку **cg-painting-v2/** (тема WP — окремий варіант).
- Файли **.env**, **.py**, **DEPLOY.md**, **THEME-STRUCTURE.md** — на роботу сайту не потрібні.

---

## Варіант 2: WordPress на Hostinger

Якщо потрібен саме WordPress (плагіни, адмінка, блог):

1. Встанови WordPress через панель Hostinger.
2. Використовуй тему **cg-painting-v2** (папка в проєкті): зроби zip **тільки папки** `cg-painting-v2` (всередині має бути `style.css` у корені теми) і встанови її як тему.
3. У темі вже є shortcode форми `[cg_contact_form]`; листи через wp_mail.
4. Щоб вигляд був максимально як у index.html, потрібно буде додатково підтягнути стилі/блоки з index.html у тему (зараз у темі спрощений набір блоків).

Для «як у нас зараз» без правок теми простіше залити сайт як у **Варіанті 1**.

---

## Локальне тестування форми (листи на пошту)

- Запуск: з кореня проєкту `python local-dev/app.py` (потрібен Python і Flask).
- Відкрити http://127.0.0.1:5000, заповнити форму — лист піде на Gmail (потрібен **GMAIL_APP_PASSWORD** у **.env**).
- Папку **local-dev/** на хостинг не завантажувати.
