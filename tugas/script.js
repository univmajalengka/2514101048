
/* =========================
   NAVIGASI MENU
========================= */

const navLinks = document.querySelectorAll(".navbar nav a");
const sections = document.querySelectorAll("section[id]");

function updateActiveLink() {
    let currentSection = "home";

    sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;

        if (window.scrollY >= sectionTop) {
            currentSection = section.id;
        }
    });

    navLinks.forEach(link => {
        link.classList.remove("active");

        if (link.getAttribute("href") === `#${currentSection}`) {
            link.classList.add("active");
        }
    });
}

// Perbarui menu saat halaman di-scroll
window.addEventListener("scroll", updateActiveLink);

// Perbarui menu saat halaman pertama kali dibuka
window.addEventListener("load", updateActiveLink);

// Perbarui menu saat navigasi diklik
navLinks.forEach(link => {
    link.addEventListener("click", () => {
        navLinks.forEach(item => item.classList.remove("active"));
        link.classList.add("active");
    });
});



/* =========================
   GALERI
========================= */

const gallery = document.getElementById("galleryContainer");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");

nextBtn.addEventListener("click", function () {
    gallery.scrollBy({
        left: gallery.clientWidth,
        behavior: "smooth"
    });
});

prevBtn.addEventListener("click", function () {
    gallery.scrollBy({
        left: -gallery.clientWidth,
        behavior: "smooth"
    });
});
