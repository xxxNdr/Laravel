function Alert(data) {
    if (data.output.length === 0) return;
    const lastObj = data.output[data.output.length - 1];
    const testo = Object.entries(lastObj)
        .map(([k, v]) => `${k}: ${v}`)
        .join("\n");

    alert(data.message + "\n\nUltimo Inserimento:\n" + testo);
}

export { Alert };
