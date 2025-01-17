import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "eb46a218b5a8eb84ac2a",
    cluster: "us3",
    forceTLS: true,
});

window.Echo.channel("public-chat").listen("MessageSent", (data) => {
    // Ganti "MessageSent" dengan nama event Anda
    console.log("Event received:", data); // Debugging: memastikan data diterima

    // Menampilkan pesan baru di UI
    const messageList = document.getElementById("messages"); // Pastikan ID sesuai dengan HTML Anda
    const li = document.createElement("li");
    li.textContent = `${data.message.username}: ${data.message.message}`;
    messageList.appendChild(li);
});
