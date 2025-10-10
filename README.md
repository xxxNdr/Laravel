# Istruzioni

## Configurazione del Database

1. Importa il file `estetica.sql` nel tuo database MySQL. Puoi farlo tramite MySQL Workbench oppure da linea di comando:

mysql -u tuo_utente -p nome_database < percorso/estetica.sql


2. Modifica il file `.env` nella root del progetto per impostare le credenziali del database:


---

## Clonazione del repository ed installazione

git clone https://github.com/xxxNdr/Laravel.git
cd Laravel/18
composer install


## Generazione della chiave dell'applicazione

php artisan key:generate


## Avvio del server locale


php artisan serve
