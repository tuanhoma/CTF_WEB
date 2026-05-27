// App configuration leaked inside JavaScript client code (Phase 1 — Recon)
const API_BASE        = "http://api.lab.local/v2/";
const STAFF_URL       = "http://staff.lab.local/";
const EXPORT_ENDPOINT = "/api/internal/export";
const REPORT_ENDPOINT = "/api/v2/report";

// Auto trigger background checking tasks
document.addEventListener("DOMContentLoaded", () => {
    console.log("[Portal Client Init] Loaded successfully.");

    // VULN: Leaked internal API endpoints triggered on page load
    fetch(API_BASE + "user/profile").catch(() => {});
    fetch("/api/internal/report").catch(() => {});
});
