create database estetica;
use estetica;

CREATE TABLE `trattamenti` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `nome` varchar(100) NOT NULL,
   `durata_minuti` int(11) NOT NULL,
   `prezzo` decimal(4,0) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY (`id`)
 );

CREATE TABLE `orari_apertura` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `giorno_settimana` tinyint(4) NOT NULL,
   `ora_inizio_mattina` time DEFAULT NULL,
   `ora_fine_mattina` time DEFAULT NULL,
   `ora_inizio_pomeriggio` time DEFAULT NULL,
   `ora_fine_pomeriggio` time DEFAULT NULL,
   `aperto` tinyint(1) DEFAULT 1,
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY (`id`)
 );

CREATE TABLE `clienti` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `nome` varchar(20) NOT NULL,
   `cognome` varchar(30) NOT NULL,
   `telefono` varchar(20) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY (`id`),
   UNIQUE KEY `telefono` (`telefono`)
 );
 
CREATE TABLE `prenotazioni` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `id_cliente` int(11) NOT NULL,
   `id_trattamento` int(11) NOT NULL,
   `data` date NOT NULL,
   `ora_inizio` time NOT NULL,
   `ora_fine` time DEFAULT NULL,
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY (`id`),
   KEY `id_cliente` (`id_cliente`),
   KEY `id_trattamento` (`id_trattamento`),
   CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clienti` (`id`) ON DELETE CASCADE,
   CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`id_trattamento`) REFERENCES `trattamenti` (`id`) ON DELETE CASCADE
 );
 
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
);

DELIMITER //
CREATE TRIGGER controllo_sovrapposizione
BEFORE INSERT ON prenotazioni
FOR EACH ROW
BEGIN
    DECLARE sovrapposizioni INT;
    DECLARE durata_trattamento INT;
    
    SELECT durata_minuti INTO durata_trattamento 
    FROM trattamenti WHERE id = NEW.id_trattamento;
    
    IF NEW.ora_fine IS NULL THEN
        SET NEW.ora_fine = ADDTIME(NEW.ora_inizio, SEC_TO_TIME(durata_trattamento * 60));
    END IF;
    
    SELECT COUNT(*) INTO sovrapposizioni
    FROM prenotazioni
    WHERE data = NEW.data
    AND (
        (NEW.ora_inizio >= ora_inizio AND NEW.ora_inizio < ora_fine)
        OR (NEW.ora_fine > ora_inizio AND NEW.ora_fine <= ora_fine)
        OR (NEW.ora_inizio <= ora_inizio AND NEW.ora_fine >= ora_fine)
    );
    
    IF sovrapposizioni > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Fascia oraria già occupata';
    END IF;
END //

-- TRIGGER PER UPDATE
CREATE TRIGGER controllo_sovrapposizione_update
BEFORE UPDATE ON prenotazioni
FOR EACH ROW
BEGIN
    DECLARE sovrapposizioni INT;
    DECLARE durata_trattamento INT;
    
    SELECT durata_minuti INTO durata_trattamento 
    FROM trattamenti WHERE id = NEW.id_trattamento;
    
    IF NEW.ora_fine IS NULL THEN
        SET NEW.ora_fine = ADDTIME(NEW.ora_inizio, SEC_TO_TIME(durata_trattamento * 60));
    END IF;
    
    SELECT COUNT(*) INTO sovrapposizioni
    FROM prenotazioni
    WHERE data = NEW.data
    AND id != NEW.id  -- ESCLUDE SE STESSO
    AND (
        (NEW.ora_inizio >= ora_inizio AND NEW.ora_inizio < ora_fine)
        OR (NEW.ora_fine > ora_inizio AND NEW.ora_fine <= ora_fine)
        OR (NEW.ora_inizio <= ora_inizio AND NEW.ora_fine >= ora_fine)
    );
    
    IF sovrapposizioni > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Fascia oraria già occupata';
    END IF;
END //
DELIMITER ;

-- GESTIONE TABELLE


select * from trattamenti;
select * from orari_apertura;
select * from clienti;
select * from users;

show create table clienti;
show create table orari_apertura;
show create table prenotazioni;
show create table trattamenti;
show create table users;

describe trattamenti;
describe orari_apertura;
describe clienti;
describe prenotazioni;

-- MODIFICHE TABELLE

ALTER TABLE trattamenti
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE orari_apertura
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE clienti
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE prenotazioni
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


-- GESTIONE TRIGGER
DROP TRIGGER IF EXISTS controllo_sovrapposizione;
SHOW TRIGGERS FROM estetica;



