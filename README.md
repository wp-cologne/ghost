# 👻 Ghost CSS

**The Invisible Architecture for Modern Web Design.**

Ghost ist ein modulares SCSS-Framework, entwickelt für maximale Flexibilität. Es arbeitet im Hintergrund und liefert Design-Tokens für Pagebuilder (wie Bricks, Oxygen) oder fungiert als leichtgewichtiges Standalone-Framework für HTML-Projekte.

## 🔥 Features

* **Invisible Core:** Keine unnötigen Klassen. Volle Kontrolle über CSS-Variablen.
* **Fluid Everything:** Typografie und Spacing skalieren flüssig (Fluid Typography & Modular Scales).
* **Grid Intelligence:** Smart Grids, die sich dem Inhalt anpassen (`--grid-auto`).
* **Token-Based:** Perfekt als Basis für Bricks Builder oder Custom Themes.

---

## 🚀 Installation & Nutzung

Du hast zwei Möglichkeiten, Ghost zu nutzen, je nach Projektart:

### Option A: Standalone (HTML / PHP)
*Nutze dies für statische Webseiten, Prototypen oder klassische PHP-Projekte.*

1.  Klone das Repository:
    ```bash
    git clone [https://github.com/DEIN-USER/ghost-css.git](https://github.com/DEIN-USER/ghost-css.git)
    ```
2.  Verlinke die CSS-Datei im `<head>` deiner Seite:
    ```html
    <link rel="stylesheet" href="path/to/ghost-css/css/standalone.css">
    ```
3.  Nutze das HTML-Gerüst (Container, Grid, etc.):
    ```html
    <section class="section">
        <div class="container">
            <h1>Hallo Ghost</h1>
            <div class="grid grid--3">
               </div>
        </div>
    </section>
    ```

### Option B: Bricks Builder / WordPress (Tokens Only)
*Nutze dies, um Bricks mit deinen Variablen zu füttern, ohne dass Framework-Styles (wie Reset oder Grid-Klassen) mit Bricks kollidieren.*

**Schritt 1: CSS einbinden**
Du kannst den Code entweder kopieren oder die Datei einbinden.

* *Methode 1 (Copy & Paste):*
    Öffne `css/tokens.css`, kopiere den Inhalt und füge ihn in Bricks unter **Settings → Custom Code → Custom CSS** ein.

* *Methode 2 (functions.php):*
    Lade die Datei sauber über dein Child-Theme:
    ```php
    function enqueue_ghost_tokens() {
        wp_enqueue_style( 'ghost-tokens', get_stylesheet_directory_uri() . '/ghost-css/css/tokens.css' );
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_ghost_tokens' );
    ```

**Schritt 2: Variablen in Bricks nutzen**
Nutze jetzt überall in den Eingabefeldern von Bricks deine Variablen (`var(...)`).

* **Breiten:** Theme Styles → Layout → Container Width: `var(--container-max-width)`
* **Farben:** Background: `var(--primary)`
* **Schriftgrößen:** Typography → Font Size: `var(--text-xl)`
* **Gaps:** Layout → Gap: `var(--grid-gap)`
* **Grid Columns:** Layout → Grid Template Columns: `var(--grid-4)`

---

## 📜 Cheat Sheet (Variablen Referenz)

Diese Variablen stehen dir automatisch zur Verfügung:

### 🎨 Farben
| Variable | Beschreibung |
| :--- | :--- |
| `var(--primary)` | Hauptfarbe |
| `var(--secondary)` | Akzentfarbe |
| `var(--bg)` | Hintergrundfarbe (Body) |
| `var(--text)` | Textfarbe (Body) |
| `var(--muted)` | Ausgegraute Elemente / Disabled |

### 📐 Spacing (Lineare Skala)
| Variable | Wert (Desktop) | Nutzung |
| :--- | :--- | :--- |
| `var(--space-xs)` | 4px | Sehr kleiner Abstand |
| `var(--space-s)` | 8px | Kleiner Abstand |
| `var(--space-m)` | 16px | Standard Abstand |
| `var(--space-l)` | 24px | Großer Abstand |
| `var(--space-xl)` | 32px | Sektions-Abstand |
| `var(--space-xxl)` | 64px | Viel Weißraum |

### 🔡 Typografie (Fluid)
| Variable | Nutzung |
| :--- | :--- |
| `var(--text-s)` | Meta-Text / Kleingedrucktes |
| `var(--text-m)` | Fließtext (Body Base) |
| `var(--text-l)` | Sublines / Einleitungen |
| `var(--text-xl)` | H3 Überschriften |
| `var(--text-xxl)` | H2 Überschriften |
| `var(--text-3xl)` | H1 / Hero Titel |

### ▦ Grid & Layout
| Variable | Funktion |
| :--- | :--- |
| `var(--container-max-width)` | Maximale Breite der Seite |
| `var(--grid-gap)` | Standard Lücke (basiert auf Space M) |
| `var(--grid-1)` bis `var(--grid-12)` | Feste Spaltenanzahl |
| `var(--grid-auto)` | Automatische Spalten (min 300px) |
| `var(--span-2)` | Element überspannt 2 Spalten |

---

## 🛠 Konfiguration & Entwicklung

Die "Seele" von Ghost liegt im Ordner `scss/abstracts/`.
Hier kannst du das System anpassen, bevor du neu kompilierst:

* `_variables.scss`: Farben, Modular Scale Ratios, Breakpoints anpassen.
* `_functions.scss`: Logik für Fluid-Berechnungen.

### Kompilieren
Ghost nutzt Dart Sass.
* **VS Code:** Nutze die "Live Sass Compiler" Extension (Glenn Marks).
* **Terminal:** `sass scss/standalone.scss css/standalone.css`

---
© 2026 WP-Cologne