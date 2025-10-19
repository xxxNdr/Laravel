import { aggiornaTabella } from "./aggiornaTabella.js";
import { Alert } from "./alert.js";

document.addEventListener("DOMContentLoaded", async () => {
    if (performance.getEntriesByType("navigation")[0].type !== "reload") return;

    try {
        const response = await fetch("/calcola-provvigioni", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
                Accept: "application/json",
            },
        });

        const data = await response.json();
        if (data.success) {
            Alert(data);
            aggiornaTabella(data.output);
        }
    } catch (err) {
        console.error("Errore:", err);
        alert("Errore durante il calcolo");
    }
});
