AWAIT, indica che il codice deve aspettare la risposta del server
per procedere a eseguire il comando successivo. L'operatore esiste grazie
alla funzione di tipo async che contiene questo codice.

FETCH, funzione standard di Javascript usata per effettuare richieste
HTTP (GET, POST ecc) dal browser. Restituisce una Promise che si estingue
con l'oggetto Response.

URL, (Endpoint) a cui viene inviata la richiesta, se inizia con / è un
percorso relativo quindi la richiesta sarà inviata al dominio del sito
web, in particolare in quella parte di percorso che segue la /.

{ ... } È un oggetto di configurazione opzioni che definisce come deve
essere fatta la richiesta.

method: "POST"
Metodo tipicamente usato per aggiornare o creare una risorsa atraverso
l'invio di dati al server.

headers:{
    "X-CSRF-TOKEN": document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content"),
    "Content-Type": "application/json",
    Accept: "application/json"
}
Gli header forniscono metadati sulla richiesta e sono fondamentali
per la comunicazione con il server, specialmente su una applicazione
web moderna sviluppata su framework.

    X-CSRF-TOKEN = header di sicurezza cruciale
    perché serve a prevenire un tipo di attacco
    informatico chiamato Cross-Site Request Forgery.

    Un malintenzionato induce un utente loggato nella mia applicazione
    ad eseguire a sua insaputa azioni non volute.
    In breve, il malintenzionato da un secondo sito che l'utente sta visitando
    oltre al primo nel quale è loggato, manda una richiesta che contiene
    i cookie di sessione dell'utente, facendo sembrare al server a cui è
    collegato l'utente che la richiesta sia partita da quest'ultimo.

    Il token CSRF è una stringa imprevedibile generata dai server
    per ogni sessione utente e inserita nell'header della richiesta.

    Il server genera il token e lo salva nella sessione utente,
    contemporaneamente lo incorpora in una parte nascosta dell'HTML
    come un tag meta o un input hidden

    Quando Javascript esegue una fetch recupera il token dal DOM,
    document.querySelector, e lo include nell'header X-CSRF-TOKEN

    Quando il server riceve la richiesta prende il valore dell'header
    X-CSRF-TOKEN e lo confronta con il token salvato nella sessione
    dell'utente. Se i token corrispondono, la richiesta è legittima

document.querySelector('meta[name="csrf-token"]')
Cerca il tag meta con name: csrf-token.

.getAttribute("content")
Dall'attributo 'content' di quel tag, estrae il valore
che è il token.

"Content-Type": "application/json"
Dice al server che il corpo della richiesta è formattato JSON

Accept: "application/json"
Dice al server che il browser si aspetta una risposta formattata
anch'essa come JSON

Al termine dell'esecuzione la variabile conterrà l'oggetto Response
restituito dal server. Questo non è un dato finale ma un oggetto che
contiene lo stato della risposta, gli header della risposta (Status, Content-Type,
Date, Cache-Control, Set-Cookie...) e metodi
per accedere al corpo della risposta, come .json() che converte un JSON
in Oggetto Javascript.

PERFORMANCEENTRY

Esempio generico di PerformanceEntry
[
  {
    name: "https://tuosito.it/style.css",
    entryType: "resource",
    startTime: 12.3,
    duration: 45.6,
    initiatorType: "link",
    nextHopProtocol: "h2",
    transferSize: 1024
  }
]


Qui vediamo un file CSS caricato come entry di tipo "resource".
Proprietà come startTime, duration e name sono comuni a tutti i PerformanceEntry.

Riassunto

performance → oggetto globale del browser per misurazioni.
performance.getEntries() → array di oggetti (PerformanceEntry).
Ogni entry ha proprietà comuni (name, entryType, startTime, duration)
e altre specifiche in base al tipo (resource, navigation, paint, ecc.).

.getEntriesByType("navigation") è solo un sottoinsieme di queste entries, filtrate per tipo “navigation”.