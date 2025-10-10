// Modulo con funzioni riutilizzabili

// Pulisce tutti i figli di un elemento DOM
export function clearContainer(container) {
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }
}

// Rimuove la classe active da tutti gli elementi di una NodeList
export function removeActiveClass(nodeList) {
    nodeList.forEach((element) => {
        element.classList.remove("active");
    });
}

// Crea un messaggio di errore o avviso
export function createMessage(text, type = "muted") {
    const msg = document.createElement("h4");
    msg.classList.add(`text-${type}`);
    msg.textContent = text;
    return msg;
}
