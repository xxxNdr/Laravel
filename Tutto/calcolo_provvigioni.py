import sqlite3
import os

script_dir = os.path.dirname(os.path.abspath(__file__))

db_path = os.path.join(script_dir, "database", "vendite.sqlite")

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

cursor.execute("SELECT id, agente, importo FROM vendite WHERE provvigione IS NULL")
vendite = cursor.fetchall()

for vendita_id, agente, importo in vendite:
    provvigione = importo * 0.1
    cursor.execute(
        "UPDATE vendite SET provvigione = ? WHERE id = ?", (provvigione, vendita_id)
    )

conn.commit()
conn.close()
