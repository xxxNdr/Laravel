import creaCelle from "./creaCelle.js";
export function aggiornaTabella(OggettiJs) {
    const tbody = document.querySelector("tbody");
    if (tbody) {
        tbody.innerHTML = "";
        OggettiJs.forEach((vendita) => {
            const tr = document.createElement("tr");

            // Celle dati
            tr.appendChild(creaCelle(vendita.agente));
            tr.appendChild(
                creaCelle("€ " + Number(vendita.importo).toFixed(2))
            );
            tr.appendChild(
                creaCelle(
                    vendita.provvigione
                        ? "€ " + Number(vendita.provvigione).toFixed(2)
                        : "<em>Da calcolare</em>"
                )
            );
            tr.appendChild(
                creaCelle(
                    new Date(vendita.data_vendita).toLocaleDateString("it-IT")
                )
            );

            // Celle Azione
            const tdAzioni = document.createElement("td");
            const linkModifica = document.createElement("a");
            linkModifica.href = `/vendite/${vendita.id}`;
            linkModifica.className = "btn btn-sm btn-outline-primary";
            linkModifica.textContent = "⚙️";

            const formElimina = document.createElement("form");
            formElimina.action = `/vendite/${vendita.id}`;
            formElimina.method = "POST";
            formElimina.className = "d-inline";

            // input CSRF
            const token = document.createElement("input");
            token.type = "hidden";
            token.name = "_token";
            // convenzione Laravel, @csrf in Blade diventa = <input type="hidden" name="_token" value="hash_unico_casuale">
            token.value = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            // input method
            const method = document.createElement("input");
            method.type = "hidden";
            method.name = "_method";
            method.value = "DELETE";

            const btnElimina = document.createElement("button");
            btnElimina.type = "submit";
            btnElimina.className = "btn btn-sm btn-outline-danger";
            btnElimina.textContent = "💣";
            btnElimina.onclick = () => confirm("Sicuro?");

            formElimina.appendChild(token);
            formElimina.appendChild(method);
            formElimina.appendChild(btnElimina);

            tdAzioni.appendChild(linkModifica);
            tdAzioni.appendChild(formElimina);
            tr.appendChild(tdAzioni);

            tbody.appendChild(tr);
        });
    }
}
