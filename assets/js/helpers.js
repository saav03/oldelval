function formatearFecha(fecha) {
    const newFecha = new Date(fecha);

    const opciones = {
        year: 'numeric',
        month: 'long',
        day: '2-digit',
    }
    return newFecha.toLocaleDateString('es-ES', opciones);
}