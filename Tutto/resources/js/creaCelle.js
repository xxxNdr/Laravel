export default function creaCelle (contenuto) {
    const td = document.createElement("td");
    td.textContent = contenuto;
    return td;
}
