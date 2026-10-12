# CG Painting – WordPress

## Форма без плагінів (Shortcode + wp_mail)

1. **Дочірня тема**  
   Створи дочірню тему для своєї теми (наприклад Twenty Twenty-Four) і працюй у ній.

2. **Підключення коду форми**  
   У дочірній темі можна зробити так:
   - **Варіант A:** Скопіюй файл `cg-contact-form.php` у корінь дочірньої теми. У `functions.php` додай в кінець:
     ```php
     require_once get_stylesheet_directory() . '/cg-contact-form.php';
     ```
   - **Варіант B:** Відкрий у редакторі теми (Appearance → Theme File Editor) `functions.php` дочірньої теми і встав у кінець вміст файлу `cg-contact-form.php` (без `<?php` на початку, якщо вже є відкриваючий тег у functions.php).

3. **Сторінка з формою**  
   Створи сторінку (наприклад «Contact» або «Offerte») і в контент встав короткод:
   ```
   [cg_contact_form]
   ```

4. **Стилі**  
   Щоб форма виглядала як на макеті (CG Painting), додай у дочірню тему CSS з основного сайту (класи: `.contact-form-panel`, `.form-row`, `.form-field`, `.input`, `.select`, `.textarea`, `.btn-primary`, `.form-message`, тощо). Можна скопіювати відповідні блоки з `index.html` у `style.css` дочірньої теми або підключити окремий файл стилів.

5. **Пошта**  
   Листи йдуть на адресу, вказану в константі `CG_CONTACT_TO` у файлі форми (за замовчуванням `hello@cgpainting.be`). На хостингу налаштуй відправку листів (SMTP або PHP mail), щоб листи не потрапляли в спам.

6. **Після відправки**  
   Користувача перенаправляє на ту саму сторінку з параметром `?cg_sent=1` або `?cg_error=1` і якорем `#contact`. На сторінці показується повідомлення «Bedankt!» або помилка.

7. **Кнопки в листі**  
   - **Bel klant:** веде на `call.php?n=...` — відкривається сторінка, яка одразу запускає дзвінок (так працює з мобільного Gmail).  
   - **In agenda zetten:** веде на `calendar-add.php` (iOS → Apple Calendar, Android → Google Calendar).  
   Обидва файли **call.php** і **calendar-add.php** (з кореня проєкту) потрібно завантажити в **корінь сайту WordPress** (теку з `index.php`, `wp-config.php`), щоб посилання з листа працювали.
