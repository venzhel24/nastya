document.addEventListener("DOMContentLoaded", function() {
    // Получаем элементы
    const modal = document.getElementById("roleModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");

    // Открытие модального окна
    openModalBtn.onclick = function() {
        modal.style.display = "block";
    }

    // Закрытие модального окна
    closeModalBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Закрытие модального окна при клике за его пределами
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
});
