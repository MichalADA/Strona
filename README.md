# Parafia św. Stanisława Biskupa i Męczennika w Andrychowie — demo nowej strony

Wersja **demonstracyjna** projektu nowej strony parafii. Nie jest to oficjalny serwis parafialny i nie jest w żaden sposób powiązany z obecną stroną `stanislaw-andrychow.pl` — obecna strona pozostaje nietknięta.

---

## 1. Co jest w tym repozytorium

| Ścieżka | Co to jest |
| --- | --- |
| `Parafia św. Stanisława.dc.html` | Klikalny prototyp front-endu (HTML). Do pokazania księdzu proboszczowi na telefonie/laptopie, bez potrzeby stawiania WordPressa. |
| `styles.css`, `parish.css` | Warstwa wizualna prototypu: system projektowy + adaptacja parafialna. |
| `assets/kosciol-dzien.jpg`, `assets/kosciol-zachod.jpg` | Fotografie kościoła dostarczone przez parafię (strona główna, „O parafii”). |
| `wordpress-theme/parafia-andrychow/` | Motyw WordPress — kompletne źródła do wdrożenia. |
| `docker-compose.yml`, `.env.example`, `docker/` | Gotowe środowisko uruchomieniowe (WordPress + MariaDB). |

Prototyp i motyw korzystają z **tego samego CSS** (`wordpress-theme/parafia-andrychow/assets/css/theme.css` to złączenie `styles.css` + `parish.css`), więc to, co widać w prototypie, wygląda tak samo po wdrożeniu.

### Treści demonstracyjne

Wszystko, co nie zostało wprost przekazane przez zamawiającego, jest oznaczone jako **„Treść demonstracyjna” / „Dane demonstracyjne”**. Dotyczy to m.in. aktualności, intencji, danych księży, historii parafii i grup parafialnych.

Dane rzeczywiste użyte w demo (przekazane przez zamawiającego): nazwa parafii, adres `ul. Starowiejska 30, 34-120 Andrychów`, telefon `33 875 33 77`, porządek Mszy Świętych, godziny kancelarii.

---

## 2. Kierunek wizualny

Utrzymany związek z klasycznym charakterem parafii, w spokojnej, książkowej formie:

Kierunek sakralny: cisza, ciepło, godność — zbudowane z typografii, koloru, przestrzeni i fotografii, nie z ornamentów.

- ciepła kość słoniowa `#f7f4ee`, tekst `#20231f`, powierzchnia `#efeae1`, hairline `#d8d2c7`,
- **głęboka zieleniń leśna `#294438`** jako jedyny kolor interakcji: ikony, linki, obrysy, stany focus, obramowanie CTA transmisji,
- **antyczne złoto `#a98a55` wyłącznie jako detal** — kreska pod nadtytułami, obwódka kręża w logo, cienka linia w stopce. Nigdy jako wypełnienie, nigdy jako tło, nigdy z efektem metalicznym,
- stopka na najgłębszym stopniu zieleni `#17251f` z tekstem w kości słoniowej — spina stronę jednym kolorem zamiast obcej czerni,
- Cormorant Garamond (nagłówki) + Lora (tekst),
- fotografia w passe-partout (`.plate`), bez karuzel i efektów,
- **tekst podstawowy 18 px** i przyciski o wysokości ≥ 48 px — świadome odejście od domyślnej gęstości systemu na rzecz czytelności dla osób starszych.

Kontrast: tekst na tle 14:1, zieleniń na kości słoniowej 9,4:1 (bezpieczna także dla tekstu akapitowego), złoto używane wyłącznie jako element graficzny, nigdy jako nośnik treści.

Bez animacji, gradientów, glassmorfizmu i pełnoekranowego hero.

---

## 3. Architektura informacji

```
Strona główna
Aktualności            (CPT: aktualnosc)      /aktualnosci/   — publicznie: „Ogłoszenia”
Intencje               (CPT: intencja)        /intencje/
Sakramenty             (strony)
  ├ Chrzest            /sakramenty/chrzest/
  ├ Małżeństwo         /sakramenty/malzenstwo/
  ├ Odwiedziny chorych /sakramenty/odwiedziny-chorych/
  └ Pogrzeb            /sakramenty/pogrzeb/
Parafia
  ├ O parafii          /o-parafii/
  ├ Historia           /historia/
  ├ Nasi księża        (CPT: ksiadz)          /ksieza/
  ├ Grupy parafialne   /grupy-parafialne/
  └ Galeria            (CPT: galeria)         /galeria/
Kontakt                /kontakt/
Transmisja na żywo     (szablon dedykowany)   /transmisja/
Ofiara na kościół      /ofiara/
Polityka prywatności   /polityka-prywatnosci/
```

**Strona główna jest bramką, nie streszczeniem serwisu.** Kolejność: fotografia i tożsamość parafii → pięć głównych akcji (Msze, Intencje, Ogłoszenia, Kontakt + wyróżniona Transmisja na żywo) → krótka zajawka dwóch ostatnich ogłoszeń → stopka. I nic więcej.

Porządek Mszy, duszpasterze, kancelaria i sakramenty **nie są** powielane na stronie głównej — mają własne strony, dostępne jednym kliknięciem z kafli lub z menu. Transmisja jest celowo największym elementem: to funkcja dla osób chorych, starszych i przebywających poza parafią.

---

## 4. Model treści

### Custom Post Types

| CPT | Po co | Pola |
| --- | --- | --- |
| `aktualnosc` | ogłoszenia i wiadomości | tytuł, treść (Gutenberg), zdjęcie wyróżniające, zajawka, data publikacji (także zaplanowana) || `intencja` | jeden wpis = jeden tydzień intencji | tytuł tygodnia + tabela wierszy (dzień, godzina, treść) |
| `ksiadz` | duszpasterze **oraz** kapłani pochodzący z parafii | tytuł = imię i nazwisko, zdjęcie, funkcja, telefon, e-mail, kolejność (`menu_order`), znacznik „kałan z naszej parafii” + rok święceń |
| `galeria` | galerie zdjęć | tytuł, data, blok Galeria |

Podstrony informacyjne (sakramenty, historia, o parafii, kontakt) to **zwykłe strony WordPressa** — nie ma sensu robić z nich CPT.

### Nazewnictwo: ogłoszenia

Parafia publikuje cotygodniowe **ogłoszenia duszpasterskie** i luźniejsze wiadomości z życia parafii. Obsługuje je **jeden** typ treści — dzielenie go byłoby przedwczesną komplikacją dla redaktora.

Publicznie nazywamy je konsekwentnie **„Ogłoszeniami”**: kafel na stronie głównej, pozycja w menu, tytuł strony docelowej („Ogłoszenia parafialne”) i etykieta w panelu mówią tym samym słowem. Gdy parafia zechce rozdzielić ogłoszenia od aktualności, wystarczy dodać taksonomię z dwoma terminami — **bez migracji istniejących wpisów**.

### Księża — dwie rozłączne grupy

Strona `/ksieza/` dzieli się na:

1. **Duszpasterze parafii** — karty z portretem, funkcją i kontaktem, sortowane polem „Kolejność”.
2. **Kapłani pochodzący z naszej parafii** — lista chronologiczna wg roku święceń, **bez numeracji** (to nie ranking).

O przynależności decyduje jedno pole wyboru w metaboksie księdza: „Kapłan pochodzący z naszej parafii” + rok święceń.

### Ustawienia globalne — jeden ekran „Parafia”

Menu **Parafia** w panelu (`inc/settings.php`, Settings API, jedna opcja tablicowa `parafia_ustawienia`):

- porządek Mszy: niedziele, niedziele lipiec–sierpień, święta zniesione, dni powszednie, adwent, dni powszednie lipiec–sierpień,
- kancelaria: godziny otwarcia, kiedy nieczynna,
- kontakt: adres, telefon, e-mail, adres osadzenia mapy,
- transmisja: publiczny adres osadzenia + zdanie opisowe,
- tryb demonstracyjny (pasek + noindex).

### Dlaczego bez ACF

Potrzebne pola to kilkanaście prostych wartości. Natywne metaboxy + Settings API nie dodają zależności, nie wygasają licencyjnie i nie blokują migracji. Dane leżą w standardowym `post_meta` o czytelnych kluczach (`_parafia_rola`, `_parafia_telefon`, `_parafia_email`, `_parafia_intencje`), więc gdyby parafia kiedyś chciała ACF, można je dołożyć bez przepisywania motywu.

---

## 5. Struktura motywu

```
wordpress-theme/parafia-andrychow/
├─ style.css                    nagłówek motywu
├─ functions.php                tylko wczytanie modułów
├─ theme.json                   paleta, typografia, szerokości dla Gutenberga
├─ inc/
│  ├─ setup.php                 supports, menu, rozmiary obrazów
│  ├─ assets.php                CSS/JS, preload LCP
│  ├─ post-types.php            CPT
│  ├─ meta-fields.php           metaboxy (księża, intencje) + zapis z nonce
│  ├─ settings.php              ekran „Parafia”
│  ├─ schedule.php              wyliczenie dzisiejszych Mszy
│  ├─ roles.php                 rola „Redaktor parafialny”
│  ├─ security.php              noindex demo, utwardzenie
│  └─ template-tags.php         helpery szablonów
├─ template-parts/
│  ├─ mass-table.php
│  └─ card-aktualnosc.php
├─ header.php  footer.php  index.php  page.php  single.php  archive.php  404.php
├─ front-page.php               strona główna
├─ single-intencja.php          tydzień intencji
├─ template-transmisja.php      szablon „Transmisja na żywo”
└─ assets/{css/theme.css, js/parafia.js}
```

Jeden arkusz CSS, jeden skrypt (~40 linii: menu mobilne + zgoda na treści zewnętrzne). Zero page-buildera, zero frameworka JS.

---

## 6. Wymagania

- WordPress **6.5+**
- PHP **8.1+** (testowane na 8.2), rozszerzenia: `mbstring`, `json`, `curl`, `gd` lub `imagick`
- MySQL 5.7+ / MariaDB 10.4+
- HTTPS (Let's Encrypt wystarczy)
- uprawnienia plików: katalogi `755`, pliki `644`, `wp-content/uploads` zapisywalny dla użytkownika serwera WWW
- zalecane: `mod_rewrite` (Apache) lub odpowiednik `try_files` (nginx)

### Wtyczki

**Wymagane:** żadne. Motyw działa samodzielnie.

**Zalecane na produkcji:**
- kopie zapasowe (np. UpdraftPlus) — obowiązkowo,
- ograniczenie prób logowania (np. Limit Login Attempts Reloaded),
- cache stron (na hostingu bez cache serwerowego — np. WP Super Cache),
- konwersja WebP/AVIF, jeśli hosting jej nie robi.

**Opcjonalne:** wtyczka SEO (Yoast/Rank Math) — WordPress sam generuje `sitemap.xml`, tytuły i kanoniczne obsługuje motyw; wtyczka przydaje się dopiero przy migracji i przekierowaniach 301.

---

## 7. Uruchomienie przez Docker (zalecane)

Najszybsza droga — nie trzeba lokalnie instalować PHP, MySQL ani serwera WWW.

```bash
cp .env.example .env      # ustaw własne hasła
docker compose up -d
```

WordPress będzie pod **http://localhost:8080** (port z `WORDPRESS_PORT`).

### Co zawiera stack

| Usługa | Obraz | Uwagi |
| --- | --- | --- |
| `wordpress` | `wordpress:6-php8.3-apache` | jedyna usługa wystawiona na host |
| `db` | `mariadb:11.4` | **bez publikacji portu**, dostępna tylko w sieci `parafia` |

phpMyAdmin świadomie pominięty — to kolejna publicznie dostępna powierzchnia ataku, a wszystko, czego potrzeba, robi `docker compose exec db mariadb`. Jeśli będzie potrzebny, dodaj go lokalnie i nie publikuj na VPS.

### Trwałość danych

- `db_data` — baza danych (named volume),
- `wp_data` — cała instalacja WordPressa wraz z `wp-content/uploads`,
- motyw jest **bind-mountem** z `./wordpress-theme/parafia-andrychow` — edytujesz PHP/CSS/JS na hoście i odświeżasz przeglądarkę, bez przebudowy obrazu.

### Zdrowie i restarty

`db` ma healthcheck (`healthcheck.sh --connect --innodb_initialized`), `wordpress` startuje dopiero po `service_healthy`. Obie usługi mają `restart: unless-stopped`, więc wstają po restarcie maszyny.

### Kroki po `docker compose up -d`

1. Otwórz **http://localhost:8080** — instalator WordPressa.
2. Wybierz język **polski**, podaj tytuł `Parafia św. Stanisława Biskupa i Męczennika`, utwórz konto administratora (silne hasło).
3. Zaloguj się do **/wp-admin**.
4. Wygląd → Motywy → **Parafia Andrychów** → Włącz. Aktywacja tworzy rolę „Redaktor parafialny”.
5. **Wtyczki: żadna nie jest wymagana.** Motyw działa samodzielnie. Zalecane dopiero na produkcji — patrz sekcja 6.
6. Ustawienia → Bezpośrednie odnośniki → **Nazwa wpisu**.
7. Utwórz strony i menu — kroki 5–8 z sekcji 7a poniżej.
8. Parafia → Transmisja na żywo → wklej publiczny adres osadzenia.
9. Sprawdź noindex — sekcja 14.

### Przydatne polecenia

```bash
docker compose logs -f wordpress     # logi
docker compose exec db mariadb -u root -p   # konsola bazy
docker compose down                  # zatrzymanie (dane zostają)
docker compose down -v               # zatrzymanie i USUNIĘCIE danych
```

---

## 7a. Instalacja bez Dockera

1. Postaw WordPressa (LocalWP, DevKinsta, `wp-env` albo XAMPP).
2. Skopiuj `wordpress-theme/parafia-andrychow/` do `wp-content/themes/`.
3. Wygląd → Motywy → **Parafia Andrychów** → Włącz. Aktywacja tworzy rolę „Redaktor parafialny” i odświeża przekierowania.
4. Ustawienia → Bezpośrednie odnośniki → **Nazwa wpisu**.
5. Utwórz strony: `Transmisja na żywo` (szablon **Transmisja na żywo**), `Msze`, `Kontakt`, `Ofiara`, cztery strony sakramentów, `O parafii`, `Historia`, `Grupy parafialne`, `Polityka prywatności`.
6. Ustawienia → Czytanie → strona główna: statyczna, wskaż utworzoną stronę startową (front-page.php i tak przejmie wyświetlanie).
7. Wygląd → Menu → utwórz menu i przypisz do **Menu główne** oraz **Menu w stopce**.
8. Wygląd → Dostosuj → dodaj fotografię nagłówkową (`parafia_hero_image`).
9. Parafia → uzupełnij godziny Mszy, kancelarię, kontakt.

---

## 8. Instrukcja dla parafii (codzienna praca)

**Dodać ogłoszenie:** Ogłoszenia → Dodaj nowe → tytuł → treść → (opcjonalnie zdjęcie wyróżniające) → Opublikuj. Data publikacji może być ustawiona z wyprzedzeniem („Zaplanuj”).

**Dodać intencje na tydzień:** Intencje mszalne → Dodaj tydzień intencji → tytuł np. „31 sierpnia – 6 września 2026” → w tabeli wpisz dzień, godzinę i treść → Zapisz. Po zapisie pojawia się kolejny pusty wiersz. Wiersz z pustą treścią jest usuwany. Stare tygodnie zostają na liście jako archiwum.

**Zmienić godziny Mszy:** Parafia → Porządek Mszy Świętych → godziny po przecinku → Zapisz. Sekcja „Dzisiaj” na stronie głównej przelicza się sama.

**Zmienić dane księdza:** Księża → wybierz wpis → tytuł to imię i nazwisko, w metaboksie funkcja/telefon/e-mail, zdjęcie w „Obrazek wyróżniający”, kolejność w „Atrybuty strony → Kolejność”. Jeśli ksiądz nie posługuje w parafii, lecz z niej pochodzi — zaznacz „Kapłan pochodzący z naszej parafii” i podaj rok święceń; trafi wtedy na listę chronologiczną.

**Dodać galerię:** Galerie → Dodaj galerię → tytuł i data → blok **Galeria** → wgraj zdjęcia → Opublikuj.

**Zmienić godziny kancelarii / kontakt:** Parafia → sekcje „Kancelaria parafialna” i „Kontakt”.

**Zmienić transmisję (tylko administrator):** Parafia → Transmisja na żywo → wklej **publiczny adres osadzenia** (YouTube / YouTube-nocookie / Vimeo). Puste pole = komunikat „Transmisja jest obecnie niedostępna.”. Nigdy nie wpisuj tu klucza transmisji ani hasła.

---

## 9. Transmisja na żywo — założenia

- Osobny szablon (`template-transmisja.php`), nie zwykła strona.
- Nad odtwarzaczem: nagłówek + jedno zdanie. Nic więcej.
- **Pod odtwarzaczem też nic** — żadnych przycisków, żadnych informacji technicznych. Użytkownik wszedł tu, żeby oglądać.
- Odtwarzacz 16:9, do 1320 px szerokości, wyśrodkowany; na telefonie pełna szerokość, tuż pod nagłówkiem.
- Gdy transmisji nie ma: „Transmisja jest obecnie niedostępna.”
- Odtwarzacz zewnętrzny ładuje się **dopiero po kliknięciu** — do tego momentu nie wysyłamy żadnych żądań do dostawcy (RODO/cookies).
- Adres osadzenia przechodzi przez whitelistę hostów (`parafia_sanitize_embed`); dowolny HTML nie jest przyjmowany.
- Znacznik „NA ŻYWO” **nie jest** wyświetlany na sztucznie — pojawi się tylko po wpięciu realnego sprawdzenia stanu z API dostawcy (poza zakresem demo).

---

## 10. Role i uprawnienia

| Rola | Zakres |
| --- | --- |
| Administrator | motyw, wtyczki, użytkownicy, aktualizacje, **konfiguracja transmisji**, wszystkie treści |
| Redaktor parafialny | aktualności, intencje, księża, galerie, strony, ekran „Parafia” bez sekcji transmisji |

Rola `parafia_redaktor` powstaje z uprawnień edytora, pomniejszonych o `unfiltered_html`, `edit_theme_options` i `manage_categories`. Nie nadawaj personelowi roli Administrator.

---

## 11. Dostępność (cel: WCAG 2.2 AA)

- tekst 18 px, interlinia 1.6, nagłówki płynne (`clamp`), nic poniżej 16 px,
- kontrast tekstu na tle > 12:1; burgund na jasnym tle > 7:1,
- `:focus-visible` — widoczna obwódka 2 px w kolorze akcentu, nigdy `outline: none`,
- cele dotykowe ≥ 48 px, kafle szybkich akcji ≥ 104 px wysokości,
- semantyczny HTML5, jeden `h1` na stronę, poprawna hierarchia nagłówków,
- link „Przejdź do treści”, `aria-expanded` na przycisku menu, `aria-label` na nawigacjach,
- rozwijane menu działa też z klawiatury (`:focus-within`), nie tylko na hover,
- opisowe linki („Czytaj więcej: *tytuł*” dla czytników ekranu), `alt` dla zdjęć.

---

## 12. Wydajność

- jeden CSS, jeden JS ~40 linii, brak bibliotek,
- fonty z `display=swap`, dwie rodziny, po dwie grubości,
- `add_image_size` dopasowane do realnych kontenerów; `srcset`/`sizes` z WordPressa,
- `loading="lazy"` poniżej pierwszego ekranu, `fetchpriority="high"` + `preload` dla fotografii hero (LCP),
- brak karuzel, brak animacji, brak zewnętrznych skryptów; mapa i wideo dopiero po kliknięciu,
- `remove_action` dla emoji i `wp_generator`.

---

## 13. Bezpieczeństwo

- brak sekretów w repozytorium; wszystkie klucze wyłącznie w `wp-config.php` na serwerze,
- każdy zapis metaboxa: `wp_verify_nonce` + `current_user_can` + sanityzacja (`sanitize_text_field`, `sanitize_email`, `esc_url_raw`),
- każde wyjście escapowane (`esc_html`, `esc_attr`, `esc_url`),
- whitelist hostów dla osadzenia transmisji,
- `xmlrpc` wyłączony, `?author=N` przekierowane, komunikat logowania nie zdradza istnienia loginu,
- nagłówki `X-Content-Type-Options`, `Referrer-Policy`,
- zalecane na produkcji: `DISALLOW_FILE_EDIT`, ograniczenie prób logowania, 2FA dla administratora, automatyczne aktualizacje bezpieczeństwa.

W `wp-config.php`:

```php
define( 'DISALLOW_FILE_EDIT', true );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
```

---

## 14. Tryb demonstracyjny i noindex

Parafia → **Tryb demonstracyjny** (domyślnie włączony) robi trzy rzeczy:

1. pokazuje pasek „Wersja demonstracyjna — projekt nowej strony parafii”,
2. wysyła `<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">`,
3. wysyła nagłówek HTTP `X-Robots-Tag: noindex, nofollow, noarchive`.

**Jak sprawdzić, że działa:**

```bash
curl -sI https://demo.przyklad.pl | grep -i x-robots-tag
# X-Robots-Tag: noindex, nofollow, noarchive

curl -s https://demo.przyklad.pl | grep -i 'name="robots"'
# <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
```

Obie linie muszą się pojawić. Dodatkowo zaznacz Ustawienia → Czytanie → „Proś wyszukiwarki o nieindeksowanie”. `robots.txt` **nie wystarcza** i nie jest jedynym zabezpieczeniem — blokuje crawl, ale nie indeksowanie adresu znalezionego z linku. Na produkcji wyłącz tryb demonstracyjny dopiero po decyzji parafii.

W demo nieaktywne są: formularz kontaktowy, płatności, newsletter. Sekcja „Ofiara na kościół” to wyłącznie projekt — przyciski są nieklikalne i opisane jako demonstracyjne.

---

## 15. Wdrożenie demo

### Mały VPS z Docker Compose i reverse proxy

Stack WWW jest **agnostyczny wobec proxy** — nie zawiera żadnego serwera brzegowego. Na VPS:

1. Zainstaluj Dockera i wtyczkę Compose. Sklonuj repozytorium, `cp .env.example .env`, ustaw **długie losowe hasła** (`openssl rand -base64 24`).
2. W `docker-compose.yml` zmień publikację portu na pętlę lokalną, żeby kontener nie był osiągalny z pominięciem proxy:
   ```yaml
   ports:
     - "127.0.0.1:${WORDPRESS_PORT}:80"
   ```
3. `docker compose up -d`.
4. Postaw reverse proxy z HTTPS. **Caddy** — najkrótsza droga, certyfikat sam się odnawia:
   ```
   demo.przyklad.pl {
       reverse_proxy 127.0.0.1:8080
       # opcjonalna ochrona hasłem na czas prezentacji:
       # basic_auth { parafia <hash-z-„caddy hash-password”> }
   }
   ```
   **Nginx** — jeśli już działa na serwerze:
   ```nginx
   server {
       server_name demo.przyklad.pl;
       location / {
           proxy_pass http://127.0.0.1:8080;
           proxy_set_header Host $host;
           proxy_set_header X-Real-IP $remote_addr;
           proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
           proxy_set_header X-Forwarded-Proto $scheme;
       }
       # auth_basic "Demo"; auth_basic_user_file /etc/nginx/.htpasswd;
   }
   ```
   Certyfikat: `certbot --nginx -d demo.przyklad.pl`.

`WORDPRESS_CONFIG_EXTRA` w compose ustawia `$_SERVER['HTTPS']` na podstawie `X-Forwarded-Proto`, więc WordPress generuje poprawne adresy `https://` za proxy.

### Czego nie wystawiać

- port bazy **3306** — nigdy publicznie; w compose nie ma sekcji `ports` dla `db`,
- phpMyAdmin — w ogóle nieobecny w stacku,
- plik `.env` — poza repozytorium (`.gitignore`), na serwerze prawa `600`,
- katalog `docker/` nie zawiera żadnych sekretów.

### Ochrona prezentacji

Demo ma zostać prywatne. Do wyboru (nieobowiązkowo): Basic Auth na proxy (przykłady wyżej), ograniczenie po IP, albo po prostu niepublikowanie adresu. Niezależnie od tego **tryb demonstracyjny z noindex zostaje włączony** — sekcja 14.

### Bez Dockera

Hosting współdzielony z PHP 8.1+ i darmowym SSL też wystarczy: subdomena, instalacja WordPressa, wgranie motywu, kroki z sekcji 7a. Nie podpinaj domeny parafii i nie ruszaj jej DNS-ów.

---

## 16. Kopie zapasowe

Do odtworzenia serwisu potrzebne są trzy rzeczy: **baza danych**, **`wp-content/uploads`** oraz **motyw i konfiguracja** (`wordpress-theme/`, `docker-compose.yml`, `.env`).

Przy Dockerze wystarczą dwie komendy:

```bash
# baza
docker compose exec -T db mariadb-dump -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" | gzip > backup/db-$(date +%F).sql.gz

# pliki wgrane przez parafię
docker compose cp wordpress:/var/www/html/wp-content/uploads ./backup/uploads-$(date +%F)
```

Odtworzenie bazy:

```bash
gunzip < backup/db-2026-09-01.sql.gz | docker compose exec -T db mariadb -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME"
```

Zasady: codziennie, retencja min. 14 dni, kopia **poza serwerem** (S3/Dropbox/Drive), test odtworzenia raz na kwartał, kopia przed każdą aktualizacją. Na hostingu bez Dockera wystarczy wtyczka UpdraftPlus.

---

## 17. Checklista migracji produkcyjnej (poza zakresem demo)

1. Inwentaryzacja starych URL-i (eksport z sitemap + crawl własnej strony za zgodą parafii).
2. Decyzja, które treści historyczne przenosimy (aktualności, galerie, historia).
3. Import treści i mediów; regeneracja miniatur.
4. Mapa przekierowań 301 stary URL → nowy URL; testy 404.
5. Weryfikacja tytułów, opisów, kanonicznych, Open Graph, `sitemap.xml`.
6. Zgoda na cookies, jeśli pojawią się narzędzia analityczne.
7. Testy wydajności (Lighthouse ≥ 90 mobile) i dostępności.
8. Ustalenie płatności online: dostawca, właściciel rachunku, regulamin, obowiązek informacyjny RODO.
9. Szkolenie personelu (1 godzina wystarcza) + skrócona instrukcja PDF.
10. Kopia zapasowa starej strony, przełączenie DNS, monitoring przez 7 dni.
11. Wyłączenie trybu demonstracyjnego, zdjęcie noindex, zgłoszenie sitemap do Google Search Console.

---

## 18. Znane ograniczenia demo

- Prototyp HTML nie jest WordPressem — służy prezentacji wyglądu i przepływów; motyw w `wordpress-theme/` to osobny, wdrażalny artefakt, który nie był uruchamiany na żywej instalacji.
- Brak fotografii poza jedną dostarczoną — pozostałe miejsca to oznaczone placeholdery.
- Dane księży, aktualności, intencje, historia i grupy są demonstracyjne.
- E-mail parafii i numer rachunku nie zostały przekazane i nie są nigdzie wymyślone.
- Mapa: w prototypie placeholder z przyciskiem zgody; w motywie osadzenie po kliknięciu z adresu ustawionego w panelu.
# Strona
