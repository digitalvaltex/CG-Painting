# CG Painting – WordPress theme skeleton

Дизайн один-в-один з [Figma](https://scrum-stroke-51049635.figma.site). Розмітка зроблена як каркас для WordPress-теми.

## Як розбити на тему

| HTML-блок в index.html | WordPress-файл / template part |
|------------------------|--------------------------------|
| `#masthead.site-header` | `header.php` або `parts/header.php` |
| `.mobile-menu` | `parts/mobile-menu.php` |
| `#main.site-main` | `front-page.php` (або `index.php`) |
| `[data-section="hero"]` | `template-parts/section-hero.php` |
| `[data-section="services"]` | `template-parts/section-services.php` |
| `[data-section="why"]` | `template-parts/section-why.php` |
| `[data-section="process"]` | `template-parts/section-process.php` |
| `[data-section="projects"]` | `template-parts/section-projects.php` |
| `[data-section="cta-banner"]` | `template-parts/section-cta-banner.php` |
| `[data-section="reviews"]` | `template-parts/section-reviews.php` |
| `[data-section="contact"]` | `template-parts/section-contact.php` |
| `[data-section="faq"]` | `template-parts/section-faq.php` |
| `#colophon.site-footer` | `footer.php` або `parts/footer.php` |

## Порядок секцій (як на Figma)

1. Hero  
2. Wat we doen (Diensten)  
3. Waarom kiezen voor CG Painting  
4. In 4 stappen (Werkwijze)  
5. Onze realisaties (Projects)  
6. **Start uw project** (CTA-banner)  
7. Wat onze klanten zeggen (Reviews)  
8. Contactformulier (Offerte)  
9. FAQ  
10. Footer  

## Класи під блоки

- `wp-block-group` – обгортка секції (FSE-стиль).
- `alignfull` – секції на повну ширину (hero, contact, cta-banner).
- Коментарі `<!-- wp:template-part ... -->` – для подальшої підміни через theme.json або FSE.

## Ассети

- Логотип: `assets/cg-painting-logo.png`
- Стилі зараз вбудовані в `index.html`; при переносі в тему винести в `style.css` або `assets/css/`.
