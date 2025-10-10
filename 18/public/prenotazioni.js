import { clearContainer, removeActiveClass, createMessage } from "./utils.js";

document.addEventListener("DOMContentLoaded", () => {
    let selectedTrattamento = null;
    let selectedOra = null;

    const calendarContainer = document.getElementById("calendar-container");
    const orariContainer = document.getElementById("orari-container");
    const dataInput = document.getElementById("data-input");
    const formContainer = document.getElementById("form-container");

    // INIZIALIZZAZIONE CALENDARIO
    flatpickr(dataInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: [(date) => date.getDay() === 0],
    });

    // SELEZIONE TRATTAMENTO
    document.querySelectorAll(".trattamento-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            removeActiveClass(document.querySelectorAll(".trattamento-btn"));
            // Rimuovi classe active da tutti i bottoni
            btn.classList.add("active");
            // Aggiungi active al bottone cliccato

            selectedTrattamento = btn.dataset.id;
            /* btn è il bottone cliccato,
            element.dataset è un oggetto che contiene tutti gli attributi data- del mio HTML
            nel mio caso data-id
            Quindi sto salvando in una variabile globale il numero ID del trattaemnto selezionato
            Questa variabile verà usata nella chiamata AJAX per sapere per quale trattamento sto
            cercando orari liberi*/
            calendarContainer.style.display = "block";
            clearContainer(orariContainer);
            /* pulisco gli orari disponibili nel caso il cliente abbia un cambio idea sul trattamento
            dopo aver visto gli orari di quello precedente */
            if (formContainer) formContainer.style.display = "none";
            selectedOra = null;
            // idem per l'ora selezionata in caso di cambio trattamento
            dataInput.value = ""; // idem per la data
        });
    });

    // SELEZIONE DATA
    dataInput.addEventListener("change", () => {
        /* change è il tipo di evento perfetto per un calendario
        perché voglio chiamare l'API solo quando l'utente ha scelto
        definitivamente la data, non a ogni minimo cambiamento */
        const data = dataInput.value;
        // dataInput.value, recupero il valore della data selezionata
        if (!selectedTrattamento) {
            alert("Seleziona un trattamento prima!");
            return;
        }
        clearContainer(orariContainer);
        /* pulizia degli orari disponibili perché scelta la data, cambiano anche gli orari disponibili
        Dopo aver pulito Ajax può inserire i bottoni delle nuove ore disponibili senza che si mescolino
        a vecchie ore disponibili...
        Senza pulizia l'utente potrebbe cliccare su un orario vecchio non disponibile */
        if (formContainer) formContainer.style.display = "none";

        // CHIAMATA AJAX ALL'API
        fetch(
            `/api/orari-disponibili?data=${data}&id_trattamento=${selectedTrattamento}`
            /* di chiama la route che punta al metodo ajaxOrariDisponibili nel controller
            e passo i parametri di data e id_trattamento */
        )
            .then((res) => res.json())
            // la risposta viene convertita in un oggetto JSON
            .then((response) => {
                if (!response.ore_libere || response.ore_libere.length === 0) {
                    /* ore_libere è la chiave usata in ajaxOrariDisponibili nel controller
                    fa riferimento ai valori degli orari disponibili */
                    // se non ci sono orari disponibili creo un messaggio di avvertimento
                    const msg = createMessage(
                        "Nessun orario disponibile per il trattamento selezionato",
                        "muted"
                    );
                    orariContainer.appendChild(msg);
                    return;
                }
                // altrimenti se ci sono orari disponibili, creo i bottoni per gli orari

                // TITOLO
                const title = document.createElement("h3");
                title.classList.add("mb-3", "fw-bold");
                title.textContent = "ORARI DISPONIBILI";
                orariContainer.appendChild(title);

                // CONTAINER BOTTONI ORARI
                const btnWrapper = document.createElement("div");
                btnWrapper.classList.add(
                    "d-flex",
                    "flex-wrap",
                    "justify-content-center",
                    "gap-2"
                );

                response.ore_libere.forEach((ora) => {
                    const btn = document.createElement("button");
                    btn.classList.add(
                        "btn",
                        "btn-outline-success",
                        "ora-btn",
                        "fw-bold"
                    );
                    btn.textContent = ora;
                    // questa è l'ora 'label' del bottone
                    btn.dataset.ora = ora;
                    // questa è il vero valore dell'ora inserito in un data attribute
                    btn.type = "button";

                    btn.addEventListener("click", () => {
                        removeActiveClass(Array.from(btnWrapper.children));
                        /* Array.from trasforma in array la HTMLCollection dei bottoni figli del div
                        e per ogni bottone gli rimuovo la classe CSS che dà l'effetto selezionato */
                        btn.classList.add("active");
                        // btn, bottone su cui ha cliccato l'utente, visualizzato come selezionato
                        selectedOra = btn.dataset.ora;
                        // aggiorno la variabile che prende l'ora dall'atributo data-ora del bottone cliccato

                        // Mostro il form dopo aver selezionato l'ora
                        if (formContainer) {
                            formContainer.style.display = "block";
                            // Popola i campi hidden del form
                            document.getElementById(
                                "form-id-trattamento"
                            ).value = selectedTrattamento;
                            document.getElementById("form-data").value = data;
                            document.getElementById("form-ora-inizio").value =
                                selectedOra;
                        }
                    });
                    btnWrapper.appendChild(btn);
                    // tutti i bottoni vengono aggiunti al div
                });
                orariContainer.appendChild(btnWrapper);
                // inserito il div nel container principale della pagina, ora gli orari sono visibili
            })
            .catch((err) => {
                console.error(err);
                const msg = createMessage(
                    "Errore nel caricamento degli orari",
                    "danger"
                );
                orariContainer.appendChild(msg);
            });
    });
});
