/* =========================================================================
   Bahadori Travel Agency — admin panel JS
   Generic CRUD via data-attributes + Fetch/AJAX.
   ========================================================================= */
(function () {
    "use strict";

    var baseUrl = window.BT_BASE_URL || "";
    var csrf = window.BT_CSRF || "";

    /* ---------- toast ---------- */
    function toast(message, type) {
        var el = document.createElement("div");
        el.className = "bt-admin-toast " + (type === "error" ? "err" : "ok");
        el.textContent = message;
        document.body.appendChild(el);
        requestAnimationFrame(function () { el.classList.add("show"); });
        setTimeout(function () {
            el.classList.remove("show");
            setTimeout(function () { el.remove(); }, 300);
        }, 3200);
    }
    window.btToast = toast;

    /* ---------- modal helpers ---------- */
    function openModal(sel) {
        var m = typeof sel === "string" ? document.querySelector(sel) : sel;
        if (m) m.classList.add("show");
    }
    function closeModal(m) {
        if (typeof m === "string") m = document.querySelector(m);
        if (m) m.classList.remove("show");
    }

    document.addEventListener("click", function (ev) {
        // open (create) modal
        var openBtn = ev.target.closest("[data-open-modal]");
        if (openBtn) {
            var sel = openBtn.getAttribute("data-open-modal");
            var modal = document.querySelector(sel);
            var form = modal ? modal.querySelector("form") : null;
            if (form) {
                form.reset();
                var idField = form.querySelector("[name=id]");
                if (idField) idField.value = "";
                var title = modal.querySelector(".bt-modal-title");
                if (title) title.textContent = (openBtn.dataset.title || "افزودن");
            }
            openModal(modal);
            return;
        }
        // close modal
        if (ev.target.closest("[data-close-modal]")) {
            closeModal(ev.target.closest(".bt-modal-backdrop"));
            return;
        }
        if (ev.target.classList.contains("bt-modal-backdrop")) {
            closeModal(ev.target);
            return;
        }
        // edit: fill form from data-json
        var editBtn = ev.target.closest(".btn-bt-edit");
        if (editBtn && editBtn.dataset.json) {
            var data = JSON.parse(editBtn.dataset.json);
            var modal = document.querySelector(".bt-modal-backdrop");
            var form = modal.querySelector("form");
            form.reset();
            Object.keys(data).forEach(function (k) {
                var input = form.querySelector("[name=" + k + "]");
                if (!input) return;
                if (input.type === "checkbox") {
                    input.checked = !!parseInt(data[k], 10);
                } else if (input.type !== "file") {
                    input.value = data[k] == null ? "" : data[k];
                }
            });
            var idField2 = form.querySelector("[name=id]");
            if (idField2) idField2.value = data.id;
            var t = modal.querySelector(".bt-modal-title");
            if (t) t.textContent = "ویرایش";
            openModal(modal);
            return;
        }
        // delete
        var delBtn = ev.target.closest(".btn-bt-del");
        if (delBtn) {
            if (!confirm("آیا از حذف این مورد مطمئن هستید؟")) return;
            // Custom delete URL (bookings / messages / newsletter) or generic entity URL.
            var url = delBtn.getAttribute("data-delete-url");
            if (!url) {
                var crudForm = document.querySelector("form[data-crud-form]");
                var entity = crudForm ? crudForm.getAttribute("data-entity") : null;
                url = baseUrl + "/admin/" + entity + "/delete";
            }
            deleteItem(url, delBtn.dataset.id, delBtn.closest("tr"));
            return;
        }
    });

    /* ---------- generic create/update submit ---------- */
    document.querySelectorAll("form[data-crud-form]").forEach(function (form) {
        form.addEventListener("submit", function (ev) {
            ev.preventDefault();
            var entity = form.getAttribute("data-entity");
            var idField = form.querySelector("[name=id]");
            var id = idField ? idField.value : "";
            var url = baseUrl + "/admin/" + entity + (id ? "/update" : "/store");
            var data = new FormData(form);

            var btn = form.querySelector('button[type="submit"]');
            var orig = btn ? btn.innerHTML : "";
            if (btn) { btn.disabled = true; btn.innerHTML = "در حال ذخیره…"; }

            fetch(url, { method: "POST", body: data, headers: { "X-Requested-With": "XMLHttpRequest" } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.status === "success") {
                        toast(res.message || "ذخیره شد.", "ok");
                        setTimeout(function () { window.location.reload(); }, 700);
                    } else {
                        var text = res.message || "خطا در ذخیره.";
                        if (res.errors) { var f = Object.values(res.errors)[0]; if (f) text = f; }
                        toast(text, "error");
                    }
                })
                .catch(function () { toast("ارتباط با سرور برقرار نشد.", "error"); })
                .finally(function () { if (btn) { btn.disabled = false; btn.innerHTML = orig; } });
        });
    });

    /* ---------- generic delete ---------- */
    function deleteItem(url, id, rowEl) {
        var body = new FormData();
        body.append("id", id);
        body.append("_csrf", csrf);
        fetch(url, { method: "POST", body: body, headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status === "success") {
                    toast(res.message || "حذف شد.", "ok");
                    if (rowEl) { rowEl.style.opacity = 0; setTimeout(function () { rowEl.remove(); }, 300); }
                    else setTimeout(function () { window.location.reload(); }, 600);
                } else {
                    toast(res.message || "خطا در حذف.", "error");
                }
            })
            .catch(function () { toast("ارتباط با سرور برقرار نشد.", "error"); });
    }

    /* ---------- sidebar toggle (mobile) ---------- */
    var toggle = document.getElementById("btSideToggle");
    if (toggle) {
        toggle.addEventListener("click", function () {
            document.getElementById("btAdminSide").classList.toggle("open");
        });
    }

    /* ---------- booking status live update ---------- */
    document.querySelectorAll(".bt-status-select").forEach(function (sel) {
        sel.addEventListener("change", function () {
            var id = sel.getAttribute("data-id");
            var status = sel.value;
            var body = new FormData();
            body.append("id", id);
            body.append("status", status);
            body.append("_csrf", csrf);
            fetch(baseUrl + "/admin/bookings/status", { method: "POST", body: body, headers: { "X-Requested-With": "XMLHttpRequest" } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    toast(res.message || "به‌روزرسانی شد.", res.status === "success" ? "ok" : "error");
                })
                .catch(function () { toast("ارتباط با سرور برقرار نشد.", "error"); });
        });
    });

    /* ---------- settings tabs ---------- */
    document.querySelectorAll(".bt-tab").forEach(function (tab) {
        tab.addEventListener("click", function () {
            var target = tab.getAttribute("data-tab");
            document.querySelectorAll(".bt-tab").forEach(function (t) { t.classList.remove("active"); });
            document.querySelectorAll(".bt-tabpane").forEach(function (p) { p.classList.remove("active"); });
            tab.classList.add("active");
            var pane = document.getElementById("tab-" + target);
            if (pane) pane.classList.add("active");
        });
    });

    /* ---------- settings save (single form) ---------- */
    var settingsForm = document.querySelector("form[data-settings-form]");
    if (settingsForm) {
        settingsForm.addEventListener("submit", function (ev) {
            ev.preventDefault();
            var btn = settingsForm.querySelector('button[type="submit"]');
            var orig = btn ? btn.innerHTML : "";
            if (btn) { btn.disabled = true; btn.innerHTML = "در حال ذخیره…"; }
            var data = new FormData(settingsForm);
            fetch(baseUrl + "/admin/settings/update", { method: "POST", body: data, headers: { "X-Requested-With": "XMLHttpRequest" } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    toast(res.message || "ذخیره شد.", res.status === "success" ? "ok" : "error");
                })
                .catch(function () { toast("ارتباط با سرور برقرار نشد.", "error"); })
                .finally(function () { if (btn) { btn.disabled = false; btn.innerHTML = orig; } });
        });
    }

    /* ---------- real-time notifications (Node server) ---------- */
    if (window.BT_NODE_URL && typeof io !== "undefined") {
        try {
            var socket = io(window.BT_NODE_URL, { transports: ["websocket", "polling"] });
            socket.on("admin:event", function (data) {
                var msg = null;
                switch (data && data.type) {
                    case "new_booking":
                        msg = "درخواست رزرو جدید از " + (data.name || "مشتری");
                        break;
                    case "new_message":
                        msg = "پیام جدید: " + (data.subject || data.name || "");
                        break;
                    case "new_subscriber":
                        msg = "عضو جدید در خبرنامه: " + (data.email || "");
                        break;
                    case "content_update":
                        msg = "محتوای " + (data.entity || "") + " به‌روزرسانی شد.";
                        break;
                }
                if (msg) toast("🔔 " + msg, "ok");
            });
        } catch (e) { /* socket optional */ }
    }
})();
