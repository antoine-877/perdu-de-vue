document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("theme-toggle");
    const icon = document.getElementById("theme-icon");

    if (!toggle || !icon) return;

    const updateThemeIcon = () => {
        const isDark = document.documentElement.classList.contains("dark");

        icon.textContent = isDark ? "☀️" : "🌙";
        toggle.setAttribute(
            "aria-label",
            isDark ? "Activer le mode clair" : "Activer le mode sombre",
        );
    };

    updateThemeIcon();

    toggle.addEventListener("click", () => {
        const isDark = document.documentElement.classList.toggle("dark");

        localStorage.setItem("theme", isDark ? "dark" : "light");
        updateThemeIcon();
    });
});
