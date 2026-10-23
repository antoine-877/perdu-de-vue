document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("theme-toggle");
    const icon = document.getElementById("theme-icon");

    if (!toggle || !icon) return;

    const updateThemeIcon = () => {
        const isDark = document.documentElement.classList.contains("dark");
        const isFrench = document.documentElement.lang.startsWith("fr");

        icon.textContent = isDark ? "☀️" : "🌙";
        toggle.setAttribute(
            "aria-label",
            isDark
                ? isFrench
                    ? "Activer le mode clair"
                    : "Enable light mode"
                : isFrench
                  ? "Activer le mode sombre"
                  : "Enable dark mode",
        );
    };

    updateThemeIcon();

    toggle.addEventListener("click", () => {
        const isDark = document.documentElement.classList.toggle("dark");

        localStorage.setItem("theme", isDark ? "dark" : "light");
        updateThemeIcon();
    });
});
