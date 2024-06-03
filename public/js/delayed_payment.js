document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('request-delayed-payment-button').addEventListener('click', function() {
        fetch(window.requestDelayedPaymentUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        }).then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.message === 'Код подтверждения отправлен в Telegram.') {
                    document.getElementById('confirmation-section').style.display = 'block';
                    document.getElementById('request-delayed-payment').style.display = 'none';
                }
            }).catch(error => console.error('Error:', error));
    });

    document.getElementById('confirm-delayed-payment-button').addEventListener('click', function() {
        const code = document.getElementById('confirmation-code').value;

        fetch(window.confirmDelayedPaymentUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ code: code })
        }).then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.message === 'Отложенный платёж подтвержден.') {
                    document.getElementById('confirmation-section').style.display = 'none';
                    document.getElementById('request-delayed-payment').style.display = 'block';

                    location.reload();
                }
            }).catch(error => console.error('Error:', error));
    });
});
