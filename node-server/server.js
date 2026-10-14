/* =========================================================================
   Bahadori Travel Agency — real-time notification server
   -------------------------------------------------------------------------
   What it does:
   - PHP (App\Core\Notifier) POSTs events to  /notify  (new booking, new
     message, new subscriber, content updates).
   - This server rebroadcasts them to every connected admin dashboard over
   Socket.IO so the "نمای کلی" screen updates live without refreshing.

   Run:
       cd node-server
       npm install
       npm start            (listens on http://127.0.0.1:3000)

   The PHP side reads host/port/secret from config/config.php -> 'node'.
   ========================================================================= */

const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const PORT = process.env.NODE_PORT || 3000;
const SECRET = process.env.NODE_SECRET || "change-this-secret-token";

const app = express();
app.use(cors());
app.use(express.json({ limit: "1mb" }));

/* ---- Health check ---- */
app.get("/", (req, res) => {
    res.json({ status: "ok", service: "bahadori-notify-server", clients: io.engine.clientsCount });
});

/* ---- Endpoint PHP calls ---- */
app.post("/notify", (req, res) => {
    // Simple shared-token auth.
    const token = req.get("X-Auth-Token");
    if (SECRET && token !== SECRET) {
        return res.status(401).json({ status: "error", message: "unauthorized" });
    }

    const payload = req.body || {};
    console.log("[notify]", new Date().toISOString(), payload.type || "event");

    // Broadcast to all connected admin dashboards.
    io.emit("admin:event", payload);

    res.json({ status: "success" });
});

/* ---- HTTP + Socket.IO ---- */
const server = http.createServer(app);
const io = new Server(server, {
    cors: { origin: true, methods: ["GET", "POST"] },
});

io.on("connection", (socket) => {
    console.log("[socket] admin connected:", socket.id);

    socket.on("disconnect", () => {
        console.log("[socket] admin disconnected:", socket.id);
    });
});

server.listen(PORT, "0.0.0.0", () => {
    console.log(`Bahadori notification server running on http://0.0.0.0:${PORT}`);
    console.log(`Waiting for events from PHP (POST /notify) ...`);
});
