<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <!-- Pusher CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.2.0/pusher.min.js"></script>
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Pusher Configuration
        const PUSHER_APP_KEY = "{{ env('PUSHER_APP_KEY') }}";
        const PUSHER_APP_CLUSTER = "{{ env('PUSHER_APP_CLUSTER') }}";
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .fixed-top {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 50;
        }

        .fixed-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 50;
        }

        .content-wrapper {
            margin-top: 3rem;
            margin-bottom: 4rem;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <nav class="bg-black text-white py-3 px-4 shadow-md fixed-top">
        <img class="img-chat" src="{{ asset('img/logo.png') }}" alt="Logo">
    </nav>

    <div class="flex flex-col flex-1 mx-auto w-full max-w-md bg-white shadow-md rounded-t-lg relative content-wrapper">
        <!-- Chat Messages -->
        <ul id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
        </ul>
    </div>

    <!-- Message Form -->
    <form id="messageForm" class="flex items-center px-4 py-3 border-t bg-gray-50 fixed-bottom">
        <input 
            type="text" 
            id="message" 
            placeholder="Type a message" 
            maxlength="255" 
           autofocus
            required 
             autocomplete="off"
            class="flex-1 px-4 py-2 border rounded-full focus:ring"
        >
        <button 
            type="submit" 
            class="ml-3 px-4 py-2 text-white bg-black rounded-full hover:bg-gray-800 focus:ring"
        >
            Send
        </button>
    </form>

    <script>
        // Inisialisasi Pusher
        const pusher = new Pusher(PUSHER_APP_KEY, {
            cluster: PUSHER_APP_CLUSTER,
            encrypted: true,
        });

        const channel = pusher.subscribe("public-chat");

        // Mendengarkan event "MessageSent"
        channel.bind("MessageSent", function (data) {
            console.log("Pesan baru diterima:", data); 

            // Tambahkan pesan baru ke UI
            const messageList = document.getElementById("messages");
            const li = document.createElement("li");
            li.textContent = `${data.message.username}: ${data.message.message}`;
            li.classList.add("px-4", "py-2", "bg-gray-200", "rounded-lg", "shadow-sm", "w-fit", "max-w-xs");
            messageList.appendChild(li);

            // Scroll otomatis ke bawah untuk pesan terbaru
            messageList.scrollTop = messageList.scrollHeight;
        });

        // Ambil pesan awal saat halaman dimuat
        function loadMessages() {
            axios.get('/get-messages')
                .then(response => {
                    const messages = response.data;
                    const messageList = document.getElementById("messages");

                    // Hapus semua pesan lama
                    messageList.innerHTML = '';

                    // Tambahkan pesan ke UI
                    messages.forEach(msg => {
                        const li = document.createElement("li");
                        li.textContent = `${msg.username}: ${msg.message}`;
                        li.classList.add("px-4", "py-2", "bg-gray-200", "rounded-lg", "shadow-sm", "w-fit", "max-w-xs");
                        messageList.appendChild(li);
                    });
                })
                .catch(error => {
                    console.error("Error loading messages:", error);
                });
        }

        loadMessages();

        // Form untuk mengirim pesan
        document.getElementById("messageForm").addEventListener("submit", function (e) {
            e.preventDefault(); 

            const messageInput = document.getElementById("message");
            const message = messageInput.value;

            axios.post('/send-message', {
                message: message,
            })
            .then(() => {
                messageInput.value = ""; 
            })
            .catch(error => {
                console.error("Error sending message:", error);
            });
        });

        let lastMessageId = 0; 

        function fetchNewMessages() {
            axios.get('/get-new-messages', {
                params: { last_message_id: lastMessageId },
            })
            .then(response => {
                const messages = response.data;
                const messageList = document.getElementById("messages");

                messages.forEach(msg => {
                    const li = document.createElement("li");
                    li.textContent = `${msg.username}: ${msg.message}`;
                    li.classList.add("px-4", "py-2", "bg-gray-200", "rounded-lg", "shadow-sm", "w-fit", "max-w-xs");
                    messageList.appendChild(li);

                    lastMessageId = msg.id;
                });

                messageList.scrollTop = messageList.scrollHeight;
            })
            .catch(error => {
                console.error("Error fetching new messages:", error);
            });
        }

        setInterval(fetchNewMessages, 4000);
    </script>
</body>

</html>
