// SANITIZZAZIONE SENZA LIBRERIE ESTERNE

// Sanitizzo stringhe
export function sanitizeString(str) {
    if (typeof str !== "string") return "";

    // Rimuovo tag e caratteri speciali
    const noTags = str.replace(/<[^>]*>/g, "");
    /*
        <[^>]*>
        cancella <, tuttociò che è dentro al tag prima che si chiuda
        e infine cancella >
        g sta per "global", elimina tutte le occorrenze trovate
    */
    const clean = noTags
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#x27")
        .replace(/\//g, "&#x2F;");

    return clean;
}

// Sanitizzo nomi (solo lettere, spazi, apostrofi e trattini)
export function sanitizeName(name, maxLength) {
    if (typeof name !== "string") return "";
    const quasiClean = sanitizeString(name);
    const clean = quasiClean
        .replace(/[^a-zA-ZÀ-ÿ\s'-]/g, "")
        .trim()
        .slice(0, maxLength);
    return clean;
}

export function sanitizeMobile(number) {
    if (typeof number !== "string") return "";
    const quasiClean = sanitizeString(number);
    const clean = quasiClean
        .replace(/[^+0-9\s]/g, "")
        .trim()
        .slice(0, 20);
    return clean;
}

// Valido i campi prima dell'invio form
export function validateForm(nome, cognome, telefono) {
    const errors = [];

    if (!nome || nome.length < 2) {
        errors.push("Il nome deve contentere almeno 2 caratteri");
    }
    if (!cognome || cognome.length < 2) {
        errors.push("Il cognome deve contentere almeno 2 caratteri");
    }
    if (!telefono || telefono.length < 10) {
        errors.push("Il numero di telefono deve essere di almeno 10 cifre");
    }
    return { Valido: errors.length === 0, errors };
    /*
    Restituisce un oggetto con due campi:
    èValido: boolean che indica se il form è valido oppure no
    errors: array di stringhe che contengono gli errori presenti nel form 
    */
}

// Sanitizza in real-time mentre l'utente scrive
export function realTime() {
    const nomeInput = document.getElementById("nome");
    const cognomeInput = document.getElementById("cognome");
    const telefonoInput = document.getElementById("telefono");

    if (nomeInput) {
        nomeInput.addEventListener("input", (e) => {
            const sanitize = sanitizeName(e.target.value, 20);
            if (e.target.value !== sanitize) {
                e.target.value = sanitize;
                e.target.setSelectionRange(sanitize.length, sanitize.length);
                /* setSelectionRange(start, end, direction)
               può selezionare una porzione di testo se il range è largo
               nel mio caso invece start ed end sono alla fine del nome inserito
               cioè dopo la sanitizzazione del nome in qualsiasi momento il cursore
               si posiziona alla fine del nome e non rimane in mezzo, se dovesse succedere */
            }
        });
    }
    if (cognomeInput) {
        cognomeInput.addEventListener("input", (e) => {
            const sanitize = sanitizeName(e.target.value, 30);
            if (e.target.value !== sanitize) {
                e.target.value = sanitize;
                e.target.setSelectionRange(sanitize.length, sanitize.length);
            }
        });
    }

    if (telefonoInput) {
        telefonoInput.addEventListener("input", (e) => {
            const sanitize = sanitizeMobile(e.target.value);
            if (e.target.value !== sanitize) {
                e.target.value = sanitize;
                e.target.setSelectionRange(sanitize.length, sanitize.length);
            }
        });
    }
}
