/* =========================================================================
   Bahadori Travel Agency — public site JS
   Handles: AJAX forms (contact / booking / newsletter), live hero search,
   inline form feedback. No frameworks — vanilla JS + Fetch.
   ========================================================================= */
(function () {
    "use strict";

    // Expose base URL for building links inside JS.
    window.BT_BASE_URL = window.BT_BASE_URL || "";

    /* ------------------------------------------------------------------
       Toast helper (small, dependency-free notifications)
       ------------------------------------------------------------------ */
    function toast(message, type) {
        var el = document.createElement("div");
        el.className = "bt-toast " + (type === "error" ? "bt-toast-err" : "bt-toast-ok");
        el.textContent = message;
        document.body.appendChild(el);
        requestAnimationFrame(function () { el.classList.add("show"); });
        setTimeout(function () {
            el.classList.remove("show");
            setTimeout(function () { el.remove(); }, 300);
        }, 3500);
    }

    // Inject minimal toast styles once.
    var style = document.createElement("style");
    style.textContent =
        ".bt-toast{position:fixed;bottom:24px;left:50%;transform:translate(-50%,20px);" +
        "padding:12px 22px;border-radius:12px;color:#fff;font-size:.9rem;z-index:9999;" +
        "opacity:0;transition:.3s;box-shadow:0 10px 30px rgba(0,0,0,.2);font-family:inherit}" +
        ".bt-toast.show{opacity:1;transform:translate(-50%,0)}" +
        ".bt-toast-ok{background:#157347}.bt-toast-err{background:#b42318}";
    document.head.appendChild(style);

    /* ------------------------------------------------------------------
       AJAX forms — any <form data-ajax-form> is submitted via Fetch.
       Expects a JSON response: {status, message, errors?}
       ------------------------------------------------------------------ */
    document.querySelectorAll("form[data-ajax-form]").forEach(function (form) {
        form.addEventListener("submit", function (ev) {
            ev.preventDefault();
            var btn = form.querySelector('button[type="submit"]');
            var msg = form.querySelector(".bt-form-msg");
            var data = new FormData(form);
            var originalBtn = btn ? btn.innerHTML : "";

            if (btn) { btn.disabled = true; btn.innerHTML = "لطفاً صبر کنید…"; }
            if (msg) { msg.className = "bt-form-msg"; msg.textContent = ""; }

            fetch(form.action, { method: "POST", body: data, headers: { "X-Requested-With": "XMLHttpRequest" } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.status === "success") {
                        toast(res.message || "انجام شد.", "ok");
                        if (msg) { msg.className = "bt-form-msg ok"; msg.textContent = res.message || "انجام شد."; }
                        form.reset();
                        if (form.dataset.successRedirect) {
                            setTimeout(function () { window.location.href = form.dataset.successRedirect; }, 1200);
                        }
                    } else {
                        var text = res.message || "خطایی رخ داد.";
                        if (res.errors) {
                            var first = Object.values(res.errors)[0];
                            if (first) { text = first; }
                        }
                        toast(text, "error");
                        if (msg) { msg.className = "bt-form-msg err"; msg.textContent = text; }
                    }
                })
                .catch(function () {
                    toast("ارتباط با سرور برقرار نشد.", "error");
                    if (msg) { msg.className = "bt-form-msg err"; msg.textContent = "ارتباط با سرور برقرار نشد."; }
                })
                .finally(function () {
                    if (btn) { btn.disabled = false; btn.innerHTML = originalBtn; }
                });
        });
    });


    /* ------------------------------------------------------------------
       Live search (hero box) — debounced fetch to /search
       ------------------------------------------------------------------ */
    var searchInput = document.getElementById("searchQuery");
    var searchBtn = document.getElementById("searchBtn");
    var resultsBox = document.getElementById("searchResults");

    if (searchInput && resultsBox) {
        var debounce;
        var baseUrl = window.BT_BASE_URL || "";

        searchInput.addEventListener("input", function () {
            clearTimeout(debounce);
            var q = searchInput.value.trim();
            if (q.length < 2) { resultsBox.innerHTML = ""; return; }
            debounce = setTimeout(function () { doSearch(q); }, 300);
        });

        if (searchBtn) {
            searchBtn.addEventListener("click", function () {
                var q = searchInput.value.trim();
                window.location.href = baseUrl + "/tours?q=" + encodeURIComponent(q);
            });
        }

        function doSearch(q) {
            fetch(baseUrl + "/search?q=" + encodeURIComponent(q), {
                headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" }
            })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.status !== "success") { return; }
                    renderResults(res.data);
                })
                .catch(function () { /* silent */ });
        }

        function renderResults(data) {
            var html = "";
            (data.destinations || []).forEach(function (d) {
                html += '<a class="bt-sr-item" href="' + baseUrl + "/destinations/" + d.slug + '">' +
                    '<img src="' + d.image + '" alt=""><div><strong>' + esc(d.title) + '</strong>' +
                    '<span>' + esc(d.country || "") + '</span></div></a>';
            });
            (data.tours || []).forEach(function (t) {
                html += '<a class="bt-sr-item" href="' + baseUrl + "/tours/" + t.slug + '">' +
                    '<img src="' + t.image + '" alt=""><div><strong>' + esc(t.title) + '</strong>' +
                    '<span>' + esc(t.location || "") + '</span></div></a>';
            });
            resultsBox.innerHTML = html;
        }

        function esc(s) {
            return String(s).replace(/[&<>"']/g, function (c) {
                return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
            });
        }
    }
})();
