       __________________
      /                  \
     |   non tutto è     |
     |      gratis       |
      \________________/
                |
                |
    ╭─────╮
   /       \
  │  X   X  │
  │    👅    │
   ╲       ╱
    ╲_____╱

import { clearContainer, removeActiveClass, createMessage } from "./utils.js";
import {
    sanitizeMobile,
    sanitizeName,
    sanitizeString,
    validateForm,
    realTime,
} from "./sanitize.js";

document.addEventListener("DOMContentLoaded", () => {
    let selectedTrattamento = null;
    let selectedOra = null;

    const calendarContainer = document.getElementById("calendar-container");
    const orariContainer = document.getElementById("orari-container");
    const dataInput = document.getElementById("data-input");
    const formContainer = document.getElementById("form-container");

    realTime();

    flatpickr(dataInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: [(date) => date.getDay() === 0],
    });

    document.querySelectorAll(".trattamento-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            removeActiveClass(document.querySelectorAll(".trattamento-btn"));
            btn.classList.add("active");

            selectedTrattamento = btn.dataset.id;
       
            calendarContainer.style.display = "block";
            clearContainer(orariContainer);
    
            if (formContainer) formContainer.style.display = "none";
            selectedOra = null;
            dataInput.value = "";
        });
    });

    dataInput.addEventListener("change", () => {
        const data = dataInput.value;
        if (!selectedTrattamento) {
            alert("Seleziona un trattamento prima!");
            return;
        }
        clearContainer(orariContainer);
   
        if (formContainer) formContainer.style.display = "none";

        fetch(
            `/api/orari-disponibili?data=${data}&id_trattamento=${selectedTrattamento}`
        
        )
            .then((res) => res.json())
            .then((response) => {
                if (!response.ore_libere || response.ore_libere.length === 0) {
                   
                    const msg = createMessage(
                        "Nessun orario disponibile per il trattamento selezionato",
                        "muted"
                    );
                    orariContainer.appendChild(msg);
                    return;
                }

                const title = document.createElement("h3");
                title.classList.add("mb-3", "fw-bold");
                title.textContent = "ORARI DISPONIBILI";
                orariContainer.appendChild(title);

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
                    btn.dataset.ora = ora;
                    btn.type = "button";

                    btn.addEventListener("click", () => {
                        removeActiveClass(Array.from(btnWrapper.children));
                        btn.classList.add("active");
                        selectedOra = btn.dataset.ora;
                        if (formContainer) {
                            formContainer.style.display = "block";

                            document.getElementById(
                                "form-id-trattamento"
                            ).value = selectedTrattamento;
                            document.getElementById("form-data").value = data;
                            document.getElementById("form-ora-inizio").value =
                                selectedOra;
                        }
                    });
                    btnWrapper.appendChild(btn);
                });
                orariContainer.appendChild(btnWrapper);
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

    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", (e) => {
            const nomeInput = document.getElementById("nome");
            const cognomeInput = document.getElementById("cognome");
            const telefonoInput = document.getElementById("telefono");

            const nome = sanitizeName(nomeInput.value, 20);
            const cognome = sanitizeName(cognomeInput.value, 30);
            const telefono = sanitizeMobile(telefonoInput.value);

            const validation = validateForm(nome, cognome, telefono);
            if (!validation.Valido) {
                e.preventDefault();
                alert(validation.errors.join("\n"));
                return false;
            }
            nomeInput.value = nome;
            cognomeInput.value = cognome;
            telefonoInput.value = telefono;
        });
    }
});
