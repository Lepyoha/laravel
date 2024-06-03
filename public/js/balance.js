function updateBalance(amount, reason) {
    $.ajax({
        type: "POST",
        url: updateBalanceUrl,
        data: {
            amount: amount,
            reason: reason,
            _token: csrfToken
        },
        success: function(response) {
            if (response.success) {
                alert('Баланс успешно обновлен!');
                location.reload(); // Перезагрузка страницы для обновления информации
            } else {
                alert('Не удалось обновить баланс.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Произошла ошибка при обновлении баланса.');
        }
    });
}
