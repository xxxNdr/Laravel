import { aggiornaTabella } from "./aggiornaTabella.js";

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
            alert(data.message + "\n\nOutput:\n" + JSON.stringify(data.output, null, 2));

            aggiornaTabella(data.output);
        }
    } catch (err) {
        console.error("Errore:", err);
        alert("Errore durante il calcolo");
    }
});
