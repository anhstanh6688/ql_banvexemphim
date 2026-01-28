<!-- Chatbox CSS -->
<style>
    /* Floating Button */
    .chat-toggle-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #0d6efd;
        /* Flat Primary */
        border-radius: 50%;
        color: white;
        font-size: 24px;
        border: none;
        cursor: pointer;
        z-index: 1050;
        transition: transform 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-toggle-btn:hover {
        transform: scale(1.05);
        background-color: #0b5ed7;
    }

    /* Chat Window */
    .chat-window {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 350px;
        height: 500px;
        background-color: #ffffff;
        /* Solid White */
        border-radius: 12px;
        z-index: 1050;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #dee2e6;
        /* Simple Border */
        transform: translateY(20px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }

    .chat-window.active {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }

    /* Header */
    .chat-header {
        background-color: #0d6efd;
        /* Flat Primary */
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #0b5ed7;
    }

    .chat-avatar {
        width: 35px;
        height: 35px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0d6efd;
        font-weight: bold;
        font-size: 18px;
    }

    /* Body */
    .chat-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        background-color: #f8f9fa;
        /* Light Gray Background */
        scroll-behavior: smooth;
    }

    /* Messages */
    .message {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 15px;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .message.bot {
        align-self: flex-start;
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-top-left-radius: 2px;
        color: #333;
    }

    .message.user {
        align-self: flex-end;
        background: #0d6efd;
        color: white;
        border-bottom-right-radius: 2px;
    }

    /* Input Area */
    .chat-footer {
        padding: 15px;
        background: white;
        border-top: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-input {
        flex: 1;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 8px 15px;
        outline: none;
        font-size: 0.9rem;
        background-color: #f8f9fa;
    }

    .chat-input:focus {
        border-color: #0d6efd;
        background-color: #fff;
    }

    .send-btn {
        background: none;
        border: none;
        color: #0d6efd;
        font-size: 1.2rem;
        cursor: pointer;
    }

    /* Quick Chips (Vertical Stack) */
    .chips-wrapper {
        width: 100%;
        margin-bottom: 15px;
    }

    .quick-chips {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
        padding: 0 5px;
    }

    .chip {
        background: #fff;
        color: #0d6efd;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #dee2e6;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
    }

    .chip:hover {
        background: #f1f3f5;
        border-color: #0d6efd;
        transform: translateY(-1px);
    }

    /* ... (Typing Indicator) ... */
    .typing .dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        background: #adb5bd;
        border-radius: 50%;
        margin-right: 3px;
        animation: typing 1s infinite;
    }

    .typing .dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing .dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }


    /* Movie Card in Chat */
    .chat-movie-card {
        display: flex;
        gap: 10px;
        background: #fff;
        padding: 8px;
        border-radius: 8px;
        margin-top: 5px;
        cursor: pointer;
        border: 1px solid #dee2e6;
        transition: background 0.2s;
    }

    .chat-movie-card:hover {
        background: #f8f9fa;
    }

    .chat-movie-img {
        width: 50px;
        height: 75px;
        object-fit: cover;
        border-radius: 4px;
    }

    .chat-movie-info h6 {
        font-size: 0.9rem;
        margin: 0;
        color: #333;
    }

    .chat-movie-info small {
        font-size: 0.75rem;
        color: #0d6efd;
    }
</style>

<!-- HTML -->
<button class="chat-toggle-btn" onclick="toggleChat()">
    <i class="fas fa-comment-dots"></i>
</button>

<div class="chat-window" id="movieChatbox">
    <div class="chat-header">
        <div class="chat-avatar"><i class="fas fa-robot"></i></div>
        <div>
            <div class="fw-bold">Movie Assistant</div>
            <div style="font-size: 0.75rem; opacity: 0.8;">Always online</div>
        </div>
        <button class="btn btn-sm text-white ms-auto" onclick="toggleChat()"><i class="fas fa-times"></i></button>
    </div>

    <div class="chat-body" id="chatMessages">
        <!-- Messages will appear here -->
    </div>

    <div class="chat-footer">
        <input type="text" class="chat-input" id="chatInput" placeholder="Ask about movies..."
            onkeypress="handleEnter(event)">
        <button class="send-btn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<!-- JS Logic -->
<script>
    let moviesCache = null;
    let isTyping = false;

    // Load Movies on Init (Lazy load)
    async function loadMovies() {
        if (!moviesCache) {
            try {
                const response = await fetch('<?php echo URLROOT; ?>/api/movies');
                const result = await response.json();
                if (result.success) {
                    moviesCache = result.data;
                }
            } catch (e) {
                console.error("Failed to load bot data", e);
            }
        }
    }

    function toggleChat() {
        const chatbox = document.getElementById('movieChatbox');
        chatbox.classList.toggle('active');

        if (chatbox.classList.contains('active')) {
            loadMovies();
            if (document.getElementById('chatMessages').children.length === 0) {
                addBotMessage("Hi! I'm your Movie Assistant. How can I help you today?");
                showQuickChips();
            }
        }
    }

    function handleEnter(e) {
        if (e.key === 'Enter') sendMessage();
    }

    function sendMessage() {
        const input = document.getElementById('chatInput');
        const text = input.value.trim();
        if (!text) return;

        displayUserMessage(text);
        input.value = '';

        // Simulate thinking
        showTyping();
        setTimeout(() => {
            processBotResponse(text);
        }, 800); // 0.8s artificial delay
    }

    function displayUserMessage(text) {
        const container = document.getElementById('chatMessages');
        const div = document.createElement('div');
        div.className = 'message user';
        div.textContent = text;
        container.appendChild(div);
        scrollToBottom();
    }

    function addBotMessage(htmlContent) {
        hideTyping();
        const container = document.getElementById('chatMessages');
        const div = document.createElement('div');
        div.className = 'message bot';
        div.innerHTML = htmlContent;
        container.appendChild(div);
        scrollToBottom();
    }

    function showTyping() {
        const container = document.getElementById('chatMessages');
        const div = document.createElement('div');
        div.className = 'message bot typing';
        div.id = 'typingIndicator';
        div.innerHTML = '<span class="dot"></span><span class="dot"></span><span class="dot"></span>';
        container.appendChild(div);
        scrollToBottom();
    }

    function hideTyping() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }

    function scrollToBottom() {
        const container = document.getElementById('chatMessages');
        container.scrollTop = container.scrollHeight;
    }

    function showQuickChips() {
        hideTyping();
        const container = document.getElementById('chatMessages');

        // Create scrollable wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'chips-wrapper';

        // Add chips
        wrapper.innerHTML = `
            <div class="quick-chips">
                <div class="chip" onclick="handleChip('What is showing?')">Now Showing</div>
                <div class="chip" onclick="handleChip('Ticket Price')">Ticket Price</div>
                <div class="chip" onclick="handleChip('Coming Soon')">Coming Soon</div>
            </div>
        `;

        container.appendChild(wrapper);
        scrollToBottom();
    }

    function handleChip(text) {
        displayUserMessage(text);
        showTyping();
        setTimeout(() => processBotResponse(text), 600);
    }

    // --- SMART LOGIC ---
    function processBotResponse(input) {
        const lowerInput = input.toLowerCase();

        // 1. Price Query
        if (keywordsMatch(lowerInput, ['price', 'cost', 'ticket', 'giá', 'tiền', 'bao nhiêu'])) {
            addBotMessage("Review standard ticket prices:<br><strong>Adults:</strong> 75,000 đ<br><strong>Students:</strong> 50,000 đ<br><strong>VIP:</strong> 150,000 đ");
            return;
        }

        // 2. Now Showing
        if (keywordsMatch(lowerInput, ['showing', 'now', 'play', 'chiếu', 'lịch'])) {
            if (!moviesCache) {
                addBotMessage("I'm having trouble connecting to the database. Please try again.");
                return;
            }
            const showingWithPosters = moviesCache.filter(m => m.status === 'showing').slice(0, 3);

            if (showingWithPosters.length > 0) {
                let html = "Here are some movies showing now:<br>";
                showingWithPosters.forEach(m => {
                    html += `<div class="chat-movie-card" onclick="window.location.href='<?php echo URLROOT; ?>/booking/movie/${m.id}'">
                        <img src="${m.poster}" class="chat-movie-img">
                        <div class="chat-movie-info">
                            <h6>${m.title}</h6>
                            <small>Click to Book</small>
                        </div>
                    </div>`;
                });
                addBotMessage(html);
            } else {
                addBotMessage("No movies are showing right now.");
            }
            return;
        }

        // 3. Coming Soon
        if (keywordsMatch(lowerInput, ['coming', 'soon', 'sắp', 'tương lai'])) { // "sắp" covers "sắp chiếu"
            if (!moviesCache) return;
            const coming = moviesCache.filter(m => m.status === 'coming_soon').slice(0, 3);
            let html = "Coming soon to theaters:<br>";
            coming.forEach(m => {
                html += `• <strong>${m.title}</strong><br>`;
            });
            addBotMessage(html);
            return;
        }

        // 4. Specific Movie Search (Fuzzy)
        if (moviesCache) {
            const foundMovie = moviesCache.find(m => lowerInput.includes(m.title.toLowerCase()));
            if (foundMovie) {
                let msg = `Found "<strong>${foundMovie.title}</strong>"!<br>`;
                msg += `<div class="chat-movie-card" onclick="window.location.href='<?php echo URLROOT; ?>/booking/movie/${foundMovie.id}'">
                        <img src="${foundMovie.poster}" class="chat-movie-img">
                        <div class="chat-movie-info">
                            <h6>${foundMovie.title}</h6>
                            <small>${foundMovie.status === 'showing' ? 'Book Now' : 'Details'}</small>
                        </div>
                    </div>`;
                addBotMessage(msg);
                return;
            }
        }

        // 5. Fallback
        addBotMessage("I'm sorry, I'm just an intern bot. Use these buttons to help me understand:");
        showQuickChips();
    }

    function keywordsMatch(text, keywords) {
        return keywords.some(keyword => text.includes(keyword));
    }
</script>