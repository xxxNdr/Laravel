import sqlite3
from utils import db_path

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

cursor.execute("SELECT id, agente, importo FROM vendite WHERE provvigione IS NULL")
vendite = cursor.fetchall()

for vendita_id, agente, importo in vendite:
    provvigione = importo * 0.1
    cursor.execute(
        "UPDATE vendite SET provvigione = ? WHERE id = ?", (provvigione, vendita_id)
    )
    print(f"La vendita {vendita_id} di {agente} ha una provvigione di {provvigione}")

conn.commit()
conn.close()
