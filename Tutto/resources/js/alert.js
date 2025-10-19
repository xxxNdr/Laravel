function Alert(data) {
    if (data.output.length === 0) return;
    const lastObj = data.output[data.output.length - 1];
    const testo = Object.entries(lastObj)
        .map(([k, v]) => `${k}: ${v}`)
        .join("\n");

    let custom = data.message
        ? data.message.replace(/127\.0\.0\.1:8000/g, "XXXNDR")
        : "";
    alert(custom + "\n\nUltimo Inserimento:\n" + testo);
}

export { Alert };
