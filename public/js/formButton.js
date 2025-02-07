function sendRequest(method, url, data = null, successCallback = null, errorCallback = null) {
    const options = {
        method: method,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        },
        body: data ? JSON.stringify(data) : null
    };

    fetch(url, options)
        .then(response => {
            if (response.status === 302 || response.status === 200) {
                return response.text();
            }
            return response.json();
        })
        .then(data => {
            try {
                if (typeof data === 'string' && data.startsWith('{')) {
                    data = JSON.parse(data);
                }

                if (successCallback) successCallback(data);
            } catch (e) {
                console.error("Ошибка при парсинге JSON:", e);
                alert("Произошла ошибка при выполнении запроса.");
            }
        })
        .catch(error => {
            if (errorCallback) {
                errorCallback(error);
            } else {
                console.error("Ошибка:", error);
                alert("Произошла ошибка при выполнении запроса.");
            }
        });
}


document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".action-link").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            const url = this.getAttribute("data-url");
            const method = this.getAttribute("data-method") || "GET";
            const confirmMessage = this.getAttribute("data-confirm");

            if (confirmMessage && !confirm(confirmMessage)) {
                return;
            }

            sendRequest(method, url, null, () => {
                this.closest("tr")?.remove();
            });
        });
    });
});
