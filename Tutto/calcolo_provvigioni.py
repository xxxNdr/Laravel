# IMPORTO IL MODULO SQLITE3 PER LA GESTIONE DEL DATABASE
import sqlite3
import os

# Ottieni il percorso della directory dello script
script_dir = os.path.dirname(os.path.abspath(__file__))

# Costruisci il percorso assoluto del database
db_path = os.path.join(script_dir, "database", "vendite.sqlite")


# CONNESSIONE AL DATABASE
conn = sqlite3.connect(db_path)
cursor = conn.cursor()
# cursor è lo strumento che esegue query e legge i risultati

# ESEGUO UNA QUERY PER SELEZIONARE LE VENDITE SENZA PROVVIGIONE CALCOLATA
cursor.execute("SELECT id, agente, importo FROM vendite WHERE provvigione IS NULL")
vendite = cursor.fetchall()

# CALCOLA PROVVIGIONE 10% PER OGNI VENDITA
for vendita_id, agente, importo in vendite:
    provvigione = round(importo * 0.1, 2)
    cursor.execute(
        "UPDATE vendite SET provvigione = ? WHERE id = ?", (provvigione, vendita_id)
    )
    # tra parentesi i valori reali da sostituire ai segnaposto ?
    print(
        f"Vendita {vendita_id} di {agente}: importo {importo:.2f} -> provvigione EUR {provvigione:.2f}"
    )

# SALVO E CHIUDO LA CONNESSIONE
conn.commit()
conn.close()

print(f"\nAggiornato!\n {len(vendite)} provvigioni calcolate")
