# Hero — відкат **правка1.0** (повернути до фото, як було)

## Що змінилось у «правка1.0»
- Замість фото справа — **відео** у форматі **reels (9:16)** з ефектом «білої імли» зліва направо (десктоп).
- На **мобільному** — повноекранне відео на фоні, затемнення, **білий текст** і кнопки.
- Потрібен файл **`assets/hero-video.mp4`** (якщо немає — показується **poster** `assets/hero-foto.jpg`).

---

## Як відкатити (2 кроки)

### 1) HTML
У `index.html` знайди секцію hero (коментар `section-hero`) і **заміни** її на блок з файлу нижче **або** скопіюй з цієї інструкції старий варіант з резервної копії git.

Повний старий HTML (фото) — див. **нижче в цьому файлі** (блок «HTML фото») або історію git до змін правка1.0.

### 2) CSS
У `index.html` видали блок стилів Hero від коментаря `/* Hero: tekst links...` до рядка перед `/* Services – sub-hero`.

**Встав** замість нього вміст файлу:

**`backups/HERO-CSS-перед-правка1.0.css`**

(у тому файлі вже є відступи як у `index.html` — 4 пробіли на рядок).

Після вставки прибери з секції `<section>` клас **`hero--video`**, якщо лишиш стару розмітку без нього.

---

## HTML старого варіанту (фото) — вставити як секція `.hero` без `hero--video`

```html
      <section class="hero wp-block-group alignfull" id="home" data-section="hero">
        <div class="container hero-inner">
          <div class="hero-left">
            <h1 class="hero-title">Schilderwerken Limburg – zonder stress. Strak resultaat, snel geregeld</h1>

            <p class="hero-subtitle">
              Binnenschilderwerken in Limburg &amp; Vlaams-Brabant. Vakmanschap, aandacht voor detail, schone oplevering.
            </p>

            <div class="hero-buttons">
              <a href="#contact" class="btn-primary">Gratis offerte aanvragen <span class="arrow">→</span></a>
              <a href="#realisaties" class="btn-secondary">Bekijk realisaties</a>
            </div>

            <div class="hero-trust-line">
              <span class="hero-stars" aria-hidden="true"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></span>
              <span class="score">4.8/5</span>
              <span class="dot" aria-hidden="true"></span>
              <span>Antwoord binnen 24u</span>
              <span class="dot" aria-hidden="true"></span>
              <span>Schilderwerken Limburg &amp; Vlaams-Brabant</span>
            </div>
          </div>

          <div class="hero-right">
            <div class="hero-image-wrap">
              <img src="assets/hero-foto.jpg" alt="Professionele schilder aan het werk – CG Painting schilderwerken Limburg" width="800" height="600" loading="eager" />
              <div class="hero-floating-badge">
                <span class="badge-dot"></span>
                <span>24u reactie</span>
              </div>
            </div>
          </div>
        </div>
      </section>
```

---

## Файли в проєкті
| Файл | Призначення |
|------|-------------|
| `backups/HERO-CSS-перед-правка1.0.css` | Повний CSS hero **до** відео |
| `HERO-REVERT-правка1.0.md` | Ця інструкція |

---

## Відео для поточної версії (не для відкату)
Додай **`assets/hero-video.mp4`** (вертикальне 9:16, H.264). Без нього браузер показує **poster** `assets/hero-foto.jpg`.
