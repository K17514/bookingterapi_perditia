<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
    /* --- Chatbot styles (minimal, responsive) --- */
    :root {
      --accent: #4f46e5;
      --bg: #0f172a;
      --card: #0b1220;
    }

    .chatbot-btn {
      position: fixed;
      right: 24px;
      bottom: 24px;
      z-index: 9999;
      border-radius: 999px;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent);
      color: #fff;
      box-shadow: 0 8px 24px rgba(79, 70, 229, 0.18);
      cursor: pointer;
    }

    .chatbot-wrap {
      position: fixed;
      right: 24px;
      bottom: 100px;
      z-index: 9999;
      width: 360px;
      max-width: calc(100% - 48px);
      font-family: Inter, Roboto, Poppins, sans-serif;
    }

    .chatbot-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(2, 6, 23, 0.2);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 520px;
    }

    .chatbot-header {
      padding: 12px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid #eee;
    }

    .chatbot-header .title {
      font-weight: 700;
    }

    .chatbot-messages {
      padding: 12px;
      overflow: auto;
      flex: 1;
      background: linear-gradient(180deg, #f7f8fc, #fff);
    }

    .msg {
      margin: 8px 0;
      max-width: 86%;
      display: inline-block;
      padding: 10px 12px;
      border-radius: 10px;
      font-size: 14px;
      line-height: 1.4;
    }

    .msg.user {
      background: #e6f4ff;
      margin-left: auto;
      border-bottom-right-radius: 2px;
    }

    .msg.bot {
      background: #f2f2f7;
      border-bottom-left-radius: 2px;
    }

    .chatbot-input {
      padding: 8px;
      border-top: 1px solid #eee;
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .chatbot-input input[type="text"] {
      flex: 1;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    .chatbot-input button {
      padding: 8px 12px;
      border-radius: 8px;
      background: var(--accent);
      color: #fff;
      border: none;
    }

    .chatbot-minimized {
      display: none;
    }

    @media (max-width:420px) {
      .chatbot-wrap {
        right: 12px;
        left: 12px;
        bottom: 80px;
        width: auto;
      }
    }
  </style>

<div
    class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 flex flex-row items-center justify-center
  bg-[#2a2521cc] border-2 border-[#a0907a] rounded-3xl shadow-[0_0_15px_rgba(0,0,0,0.4)]
  backdrop-blur-sm p-3 space-x-3 select-none w-[70%] max-w-sm
  md:bottom-auto md:top-1/2 md:right-6 md:left-auto md:translate-x-0 md:-translate-y-1/2
  md:flex-col md:items-center md:justify-center md:space-x-0 md:space-y-3
  md:w-auto md:max-w-none md:rounded-2xl md:p-2 md:bg-[#2a2521cc] md:border-[#a0907a] md:shadow-[0_0_10px_rgba(0,0,0,0.3)] md:backdrop-blur-md
  md:h-96
  ">
    <!-- Reservation -->
    <!-- Reservation -->
    <button aria-label="Reservation"
        class="flex flex-col items-center justify-center text-[#d6c9b9] text-xs font-semibold
    hover:text-white hover:scale-110 transition-transform duration-150 w-16 md:w-14"
        onclick="window.location.href='reservasi'">
        <div class="bg-[#3b2f25] p-2 rounded-lg border border-[#a0907a] hover:border-white shadow-inner">
            <i class="fa-solid fa-calendar-check text-xl"></i>
        </div>
        <span class="mt-1 hidden md:block">Reservation</span>
    </button>

    <!-- Gallery -->
    <button aria-label="Profile"
        class="flex flex-col items-center justify-center text-[#d6c9b9] text-xs font-semibold
    hover:text-white hover:scale-110 transition-transform duration-150 w-16 md:w-14"
        onclick="window.location.href='histori'">
        <div class="bg-[#3b2f25] p-2 rounded-lg border border-[#a0907a] hover:border-white shadow-inner">
            <i class="fa-solid fa-image text-xl"></i>
        </div>
        <span class="mt-1 hidden md:block">Profile</span>
    </button>

    <!-- Menu -->
    <button aria-label="Logout"
        class="flex flex-col items-center justify-center text-[#d6c9b9] text-xs font-semibold
    hover:text-white hover:scale-110 transition-transform duration-150 w-16 md:w-14"
        onclick="/* your logout logic */">
        <div class="bg-[#3b2f25] p-2 rounded-lg border border-[#a0907a] hover:border-white shadow-inner">
            <i class="fa-solid fa-right-from-bracket text-xl"></i>
        </div>
        <span class="mt-1 hidden md:block">Logout</span>
    </button>

    <!-- Chatbot -->
    <button aria-label="Chatbot"
        class="relative flex flex-col items-center justify-center text-[#d6c9b9] text-xs font-semibold
    hover:text-white hover:scale-110 transition-transform duration-150 w-16 md:w-14"
        onclick="toggleChatbot()">
        <div class="bg-[#3b2f25] p-2 rounded-lg border border-[#a0907a] hover:border-white relative shadow-inner">
            <i class="fa-solid fa-comment text-xl"></i>
            <span
                class="absolute -top-2 -left-1 bg-[#d33c3c] text-white rounded px-1 text-[10px] font-bold select-none border border-[#a0907a]">
                !
            </span>
        </div>
        <span class="mt-1 hidden md:block">Chat</span>
    </button>



</div>

<!-- ===== Chatbot UI ===== -->
<div class="chatbot-wrap" id="chatbotWrap" aria-hidden="true" style="display:none;">
  <div class="chatbot-card" role="dialog" aria-label="Ellie Chatbot">
    <div class="chatbot-header">
      <div>
        <div class="title">Aponia — Asisten Filosofis</div>
        <div style="font-size:12px;color:#6b7280">Tanyakan tentang nasib, eksistensi, atau bantuan lain.</div>
      </div>
      <div style="margin-left:auto">
        <button id="chatCloseBtn" title="Tutup"
          style="border:none;background:transparent;cursor:pointer;font-size:18px">✕</button>
      </div>
    </div>
    <div class="chatbot-messages" id="chatMessages"></div>
    <form id="chatForm" class="chatbot-input" onsubmit="return false;">
      <input id="chatInput" type="text" autocomplete="off" placeholder="Ketik pesan..." />
      <button id="chatSendBtn" type="submit">Kirim</button>
    </form>
  </div>
</div>

<script>
  // --- Simple frontend chatbot logic ---
  const chatToggleBtn = document.getElementById('chatToggleBtn');
  const chatbotWrap = document.getElementById('chatbotWrap');
  const chatCloseBtn = document.getElementById('chatCloseBtn');
  const chatMessages = document.getElementById('chatMessages');
  const chatForm = document.getElementById('chatForm');
  const chatInput = document.getElementById('chatInput');

  function appendMessage(text, who = 'bot') {
    const div = document.createElement('div');
    div.className = 'msg ' + (who === 'user' ? 'user' : 'bot');
    div.textContent = text;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return div;
  }

  function typeMessage(targetEl, text, delay = 20) {
    let i = 0;
    targetEl.textContent = "";
    const timer = setInterval(() => {
      targetEl.textContent += text.charAt(i);
      chatMessages.scrollTop = chatMessages.scrollHeight;
      i++;
      if (i >= text.length) clearInterval(timer);
    }, delay);
  }

  function toggleChatbot() {
    const isOpen = chatbotWrap.style.display !== 'none';
    chatbotWrap.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) { chatInput.focus(); }
  }

  chatCloseBtn.addEventListener('click', () => {
    chatbotWrap.style.display = 'none';
  });

  // Welcome message
  appendMessage('I do not judge you. The path you walk was never yours to choose, yet you walk it all the same. What is on your mind?');

  // Handle form submit
  chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const text = chatInput.value.trim();
    if (!text) return;
    appendMessage(text, 'user');
    chatInput.value = '';

    // Typing indicator
    const typing = appendMessage('...', 'bot');

    try {
      const res = await fetch('api/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: text })
      });

      typing.remove();

      if (!res.ok) throw new Error('Server Error: ' + res.status);
      const data = await res.json();

      if (data && data.reply) {
        const replyBox = appendMessage('', 'bot');
        typeMessage(replyBox, data.reply, 15); // typing animation
      } else {
        appendMessage('Maaf, saya belum bisa menjawab pertanyaan itu.', 'bot');
      }

    } catch (err) {
      typing.remove();
      appendMessage('⚠️ Tidak dapat terhubung ke server. Pastikan koneksi aktif dan endpoint `/api/chat` tersedia.', 'bot');
      console.error('Chatbot error:', err);
    }
  });

  // Optional: press Enter to send
  chatInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      chatForm.dispatchEvent(new Event('submit'));
    }
  });
</script>
