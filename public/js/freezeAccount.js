document.addEventListener('DOMContentLoaded', function() {
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    const freezeAccountModal = document.getElementById('freezeAccountModal');
    const freezeAccountButton = document.getElementById('freeze-account-button');

    if (freezeAccountButton) {
        freezeAccountButton.addEventListener('click', function() {
            freezeAccountModal.style.display = 'block';
        });
    }

    const closeModalButtons = document.querySelectorAll('.modal-close');
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            button.closest('.modal').style.display = 'none';
        });
    });

    const freezeRequestButton = document.getElementById('freeze-request-button');
    const confirmFreezeButton = document.getElementById('confirm-freeze-button');

    if (freezeRequestButton) {
        freezeRequestButton.addEventListener('click', function(event) {
            event.preventDefault();
            const endDate = document.getElementById('end_date').value;
            const freezeType = document.querySelector('input[name="freeze_type"]:checked').value;

            fetch('/request-freeze-account', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ end_date: endDate, freeze_type: freezeType })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        document.getElementById('end_date_hidden').value = endDate;
                        document.getElementById('freeze_type_hidden').value = freezeType;
                        document.getElementById('confirm-freezing-dates').style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    }

    if (confirmFreezeButton) {
        confirmFreezeButton.addEventListener('click', function(event) {
            event.preventDefault();
            const code = document.getElementById('confirmation_code').value;
            const endDate = document.getElementById('end_date_hidden').value;
            const freezeType = document.getElementById('freeze_type_hidden').value;

            fetch('/confirm-freeze-account', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ code: code, end_date: endDate, freeze_type: freezeType })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    }
});
