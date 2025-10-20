import os

def trova(root, nome='vendite.sqlite'):
    if not os.path.isdir(root):
        raise NotADirectoryError(f"{root} non è una cartella valida")
    
    for dirpath, dirnames, filenames in os.walk(root):
        # os.walk funzione che ripercorre ricorsivamente tutte le cartelle a partire da una root
        if nome in filenames:
            return os.path.join(dirpath, nome)
        # faccio join perché os.walk restituisce la cartella
        # per il percorso completo serve anche nome file
    return None

# Determina root del progetto Laravel
root_dir = os.getenv("LARAVEL_ROOT", os.path.dirname(os.path.abspath(__file__)))

# Trova il DB ovunque nel progetto
db_path = trova(root_dir)
if not db_path:
    raise FileNotFoundError("Non ho trovato vendite.sqlite nel progetto!")

print(f"DB trovato in: {db_path}")