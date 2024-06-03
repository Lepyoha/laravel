
function telegramBot() {
    window.open("https://t.me/diplom_k_a_e_test_bot", "_blank");
}

function linkTelegram() {
    document.getElementById("telegram-instruction").style.display = "block";
    document.getElementById("telegram-link-button").style.display = "none";
}

function addTelegramID() {
    const telegramId = document.getElementById("telegram-id-input").value;
    console.log("Telegram ID пользователя:", telegramId);
    $.ajax({
        type: "POST",
        url: addTelegramIDUrl,
        data: {
            telegram_id: telegramId,
            _token: csrfToken
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function changeTelegramID() {
    document.getElementById("telegram-instruction").style.display = "block";
}

function unlinkTelegram() {
    $.ajax({
        type: "POST",
        url: unlinkTelegramIDUrl,
        data: {
            _token: csrfToken
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}
