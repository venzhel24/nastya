document.addEventListener("DOMContentLoaded", function () {
    function checkScroll() {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
            document.body.classList.add("scrolled-to-bottom");
        } else {
            document.body.classList.remove("scrolled-to-bottom");
        }
    }

    window.addEventListener("scroll", checkScroll);
});

document.addEventListener("DOMContentLoaded", function () {
    const clearCacheBtn = document.getElementById("clear-cache-btn");
    const cacheStatus = document.getElementById("cache-status");

    if (clearCacheBtn) {
        clearCacheBtn.addEventListener("click", function () {
            clearCacheBtn.disabled = true;
            cacheStatus.innerText = "Очистка кеша...";

            fetch(clearCacheBtn.dataset.url, { method: "POST" })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "queued") {
                        setTimeout(() => {
                            clearCacheBtn.disabled = false;
                            cacheStatus.innerText = "Кеш очищен!";
                        }, 6000);
                    }
                })
                .catch(error => {
                    console.error("Ошибка при очистке кеша:", error);
                    cacheStatus.innerText = "Ошибка!";
                    clearCacheBtn.disabled = false;
                });
        });
    }
});
