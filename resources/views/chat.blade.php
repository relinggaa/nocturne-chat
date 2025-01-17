<!DOCTYPE html>
<html>
<head>
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
    <style>
        #chatBox {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        #messages {
            list-style-type: none;
            padding: 0;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        #messages li {
            padding: 5px 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        #messageForm {
            display: flex;
        }
        #messageForm input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        #messageForm button {
            padding: 10px 15px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        #messageForm button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div id="chatBox">
        <ul id="messages">
           
        </ul>
        <form id="messageForm">
            <input type="text" id="message" placeholder="Type a message" maxlength="255" required>
            <button type="submit">Send</button>
        </form>
    </div>

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
