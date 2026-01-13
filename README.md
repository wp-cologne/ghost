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
    git clone https://github.com/wp-cologne/ghost.git
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

### Option B: WordPress 
Ghost CSS beinhaltet ein natives WordPress Plugin. Erstelle einen Ordner (ghost-css) unter wp-content/plugins/ und füge die Dateien und Ordner dieses Repositries hinzu.

---

## 📜 Cheat Sheet (Variablen Referenz)

Diese Variablen stehen dir automatisch zur Verfügung:

### Farben
| Variable | Beschreibung |
| :--- | :--- |
| `var(--primary)` | Hauptfarbe |
| `var(--secondary)` | Akzentfarbe |
| `var(--bg)` | Hintergrundfarbe (Body) |
| `var(--text)` | Textfarbe (Body) |
| `var(--muted)` | Ausgegraute Elemente / Disabled |

### Spacing (Lineare Skala)
| Variable | Wert (Desktop) | Nutzung |
| :--- | :--- | :--- |
| `var(--space-xs)` | 4px | Sehr kleiner Abstand |
| `var(--space-s)` | 8px | Kleiner Abstand |
| `var(--space-m)` | 16px | Standard Abstand |
| `var(--space-l)` | 24px | Großer Abstand |
| `var(--space-xl)` | 32px | Sektions-Abstand |
| `var(--space-xxl)` | 64px | Viel Weißraum |

### Typografie (Fluid)
| Variable | Nutzung |
| :--- | :--- |
| `var(--text-s)` | Meta-Text / Kleingedrucktes |
| `var(--text-m)` | Fließtext (Body Base) |
| `var(--text-l)` | Sublines / Einleitungen |
| `var(--text-xl)` | H3 Überschriften |
| `var(--text-xxl)` | H2 Überschriften |
| `var(--text-3xl)` | H1 / Hero Titel |

### Border Radius
| Variable | Wert (Desktop) | Nutzung |
| :--- | :--- | :--- |
| `var(--radius-none)` | 0px | Kein Radius |
| `var(--radius-xs)` | 2px | Sehr kleiner Radius |
| `var(--radius-s)` | 4px | Kleiner Radius |
| `var(--radius-m)` | 8px | Standard Radius |
| `var(--radius-l)` | 12px | Großer Radius |
| `var(--radius-xl)` | 16px | Größerer Radius |
| `var(--radius-xxl)` | 32px |Noch größerer Radius|
| `var(--radius-circle)` | 500vh | Kreis-Radius |

### Grid & Layout
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
