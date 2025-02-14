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