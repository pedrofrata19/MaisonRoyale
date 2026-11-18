document.addEventListener('DOMContentLoaded', function () {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const formReserva = document.getElementById('form-reserva');
    const total = document.getElementById('total-reserva');

    if (checkIn && checkOut) {
        checkIn.addEventListener('change', function () {
            if (checkIn.value) {
                const proximoDia = new Date(checkIn.value + 'T00:00:00');
                proximoDia.setDate(proximoDia.getDate() + 1);
                checkOut.min = proximoDia.toISOString().slice(0, 10);

                if (checkOut.value && checkOut.value <= checkIn.value) {
                    checkOut.value = checkOut.min;
                }
            }
        });
    }

    function atualizarTotal() {
        if (!formReserva || !total || !checkIn || !checkOut) {
            return;
        }

        const preco = parseFloat(formReserva.dataset.preco) || 0;
        const entrada = new Date(checkIn.value + 'T00:00:00');
        const saida = new Date(checkOut.value + 'T00:00:00');

        if (!checkIn.value || !checkOut.value || saida <= entrada) {
            total.textContent = 'R$ 0,00';
            return;
        }

        const diarias = Math.round((saida - entrada) / 86400000);
        total.textContent = 'R$ ' + (diarias * preco).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    if (checkIn && checkOut) {
        checkIn.addEventListener('change', atualizarTotal);
        checkOut.addEventListener('change', atualizarTotal);
        atualizarTotal();
    }
});
