#!/usr/bin/env bash
# Odtwarza wordpress-theme/parafia-andrychow/assets/css/theme.css jako kopię 1:1
# arkuszy prototypu (wariant zielony): styles.css + parish-zielen.css.
# Jedyna zmiana: @import fontów zastąpiony komentarzem — motyw ładuje fonty
# przez wp_enqueue_style( 'parafia-fonts' ).
set -euo pipefail
cd "$(dirname "$0")/.."
OUT=wordpress-theme/parafia-andrychow/assets/css/theme.css
{
  printf '%s\n' \
    "/* Parafia Andrychów — warstwa wizualna (wariant kolorystyczny: zieleń)." \
    "   KOPIA 1:1 arkuszy prototypu: styles.css + parish-zielen.css (w tej kolejności)." \
    "   Nie edytuj ręcznie — zmiany wprowadzaj w prototypie i odtwórz plik poleceniem:" \
    "   scripts/build-theme-css.sh. Dodatki specyficzne dla WordPressa: assets/css/wp.css. */" \
    ""
  sed "s#^@import url('https://fonts.googleapis.com.*#/* fonty ładowane przez wp_enqueue_style( 'parafia-fonts' ) */#" styles.css
  printf '\n\n/* ================= parish-zielen.css ================= */\n\n'
  cat parish-zielen.css
} > "$OUT"
echo "Zapisano $OUT"
