# hackheroes-hackathon
App for hackheroes 2020

## Krótki opis / Overview
### PL:
Emodiary to aplikacja, która pozwala Ci śledzić swój stan emocjonalny i dzielić się nim publicznie, także na Facebooku.
Oryginalnie stworzona jako aplikacja dla ludzi ze spektrum autyzmu, w późniejszym okresie stała się ogólnym dziennikiem emocji.
### EN:
Emodiary is an app that allows you to track your emotional state and share it with public and your Facebook feed.
Originally meant as an app mainly for autistic spectrum people, this has expanded to be a general-purpose emotion diary.

## Instrukcje instalacji / Install instructions
### PL:
- wypakuj plik ZIP z GitHuba który dostaniesz po pobraniu kodu, do folderu public_html na serwerze WWW
- zmień wartości SQL w pliku config.php w (folder public_html)/PHP/config.php
- uruchom wszystkie migracje z (folder public_html)/migrations
  - emotions_migration.php
  - hearts_migration.php
  - kindwords_migration.php
  - notifs_migration.php
  - user_migration.php
- usuń pliki migracji z serwera WWW dla bezpieczeństwa
### EN:
- unpack the ZIP file from the GitHub code download to the public_html folder of your web server
- update SQL variables in config.php in (public_html folder)/PHP/config.php
- run all migrations from (public_html_folder)/migrations
  - emotions_migration.php
  - hearts_migration.php
  - kindwords_migration.php
  - notifs_migration.php
  - user_migration.php
- delete migration files from your web server for safety
