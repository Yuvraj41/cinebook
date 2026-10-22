
<div id="cinebook-chat-widget" class="fixed bottom-6 right-6 z-50">

    <!-- Floating chat icon -->
    <button
        id="cinebook-chat-toggle"
        type="button"
        aria-label="Open CineBook AI chat"
        aria-expanded="false"
        class="ml-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-600 text-white shadow-xl transition hover:scale-105 hover:bg-red-700"
    >
        <svg id="cinebook-chat-icon" xmlns="http://www.w3.org/2000/svg"
             width="26" height="26" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.5 8.5 0 0 1-12.5 7.5L3 21l2-5.5A8.5 8.5 0 1 1 21 11.5Z"/>
            <path d="M8 10h8M8 14h5"/>
        </svg>
    </button>

    <!-- Chat popup -->
    <section
        id="cinebook-chat-panel"
        role="dialog"
        aria-label="CineBook AI assistant"
        class="absolute bottom-16 right-0 hidden h-[min(520px,70vh)] w-[min(360px,calc(100vw-2rem))] flex-col overflow-hidden rounded-2xl border border-slate-700 bg-slate-950 shadow-2xl"
    >
        <!-- Header -->
        <div class="flex items-center justify-between bg-red-600 px-4 py-3">
            <div>
                <h2 class="font-bold text-white">CineBook AI</h2>
                <p class="text-xs text-red-100">Your personal movie assistant</p>
            </div>

            <button
                id="cinebook-chat-close"
                type="button"
                aria-label="Close chat"
                class="rounded-lg px-3 py-1 text-xl text-white hover:bg-red-700"
            >×</button>
        </div>

        <!-- Messages -->
        <div
            id="cinebook-widget-messages"
            aria-live="polite"
            class="flex-1 space-y-3 overflow-y-auto p-3 text-sm"
        >
            <div class="max-w-[90%] rounded-xl bg-slate-800 p-3 text-slate-100">
                Hey! 👋 Tell me your mood and I'll help you find a movie.
            </div>
        </div>

        <!-- Error -->
        <p id="cinebook-widget-error"
           role="alert"
           class="hidden px-3 pb-2 text-xs text-red-400"></p>

        <!-- Input -->
        <form id="cinebook-widget-form"
              class="flex gap-2 border-t border-slate-800 p-3">
            @csrf

            <input
                id="cinebook-widget-input"
                type="text"
                maxlength="1000"
                required
                autocomplete="off"
                placeholder="Ask for a movie..."
                class="min-w-0 flex-1 rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white outline-none focus:border-red-500"
            >

            <button
                id="cinebook-widget-send"
                type="submit"
                class="rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-50"
            >Send</button>
        </form>
    </section>
</div>


<script>
(() => {
    const toggle = document.getElementById('cinebook-chat-toggle');
    const panel = document.getElementById('cinebook-chat-panel');
    const close = document.getElementById('cinebook-chat-close');
    const form = document.getElementById('cinebook-widget-form');
    const input = document.getElementById('cinebook-widget-input');
    const messages = document.getElementById('cinebook-widget-messages');
    const send = document.getElementById('cinebook-widget-send');
    const error = document.getElementById('cinebook-widget-error');

    if (!toggle || toggle.dataset.initialized === 'true') return;
    toggle.dataset.initialized = 'true';

    function openChat() {
        panel.classList.remove('hidden');
        panel.classList.add('flex');
        toggle.setAttribute('aria-expanded', 'true');
        input.focus();
    }

    function closeChat() {
        panel.classList.add('hidden');
        panel.classList.remove('flex');
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', () => {
        panel.classList.contains('hidden') ? openChat() : closeChat();
    });

    close.addEventListener('click', closeChat);

    function addMessage(text, sender) {
        const bubble = document.createElement('div');

        bubble.className = sender === 'user'
            ? 'ml-auto max-w-[90%] rounded-xl bg-red-600 p-3 text-white whitespace-pre-wrap break-words'
            : 'mr-auto max-w-[90%] rounded-xl bg-slate-800 p-3 text-slate-100 whitespace-pre-wrap break-words';

        bubble.textContent = text;
        messages.appendChild(bubble);
        messages.scrollTop = messages.scrollHeight;

        return bubble;
    }

    function addMovieCards(movies) {
        movies.forEach(movie => {
            const card = document.createElement('div');

            card.className =
                'mr-auto flex w-full max-w-[300px] gap-3 rounded-xl border border-slate-700 bg-slate-900 p-3';

            if (movie.poster_url) {
                const poster = document.createElement('img');
                poster.className =
                    'h-28 w-20 shrink-0 rounded-lg bg-slate-800 object-cover';
                poster.alt = movie.title + ' poster';
                poster.src = movie.poster_url.startsWith('http')
                    ? movie.poster_url
                    : '/storage/' + movie.poster_url.replace(/^\/?storage\//, '');
                poster.onerror = () => poster.remove();
                card.appendChild(poster);
            }

            const details = document.createElement('div');
            details.className = 'min-w-0 flex-1';

            const title = document.createElement('h3');
            title.className = 'font-semibold text-white break-words';
            title.textContent = movie.title;

            const info = document.createElement('p');
            info.className = 'mt-1 text-xs text-slate-400';
            info.textContent = [
                movie.genre,
                movie.duration ? movie.duration + ' min' : null,
                movie.language
            ].filter(Boolean).join(' · ');

            const link = document.createElement('a');
            link.className =
                'mt-3 inline-block rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700';
            link.href = movie.showtimes_url;
            link.textContent = 'View Showtimes';

            details.append(title, info, link);
            card.appendChild(details);
            messages.appendChild(card);
        });

        messages.scrollTop = messages.scrollHeight;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = input.value.trim();
        if (!message || send.disabled) return;

        error.textContent = '';
        error.classList.add('hidden');

        // Show the user's message immediately.
        addMessage(message, 'user');
        input.value = '';
        messages.scrollTop = messages.scrollHeight;
        
        input.disabled = true;
        send.disabled = true;
        send.textContent = 'Thinking...';

        const loading = addMessage(
            'Finding a movie for you...',
            'assistant'
        );

        try {
            const response = await fetch(@json(route('chatbot.chat')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            loading.remove();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Unable to get a response.'
                );
            }

            addMessage(data.reply, 'assistant');

            if (Array.isArray(data.movies) && data.movies.length > 0) {
                addMovieCards(data.movies);
            }
        } catch (err) {
            loading.remove();
            error.textContent = err.message || 'Something went wrong.';
            error.classList.remove('hidden');
        } finally {
            input.disabled = false;
            send.disabled = false;
            send.textContent = 'Send';
            input.focus();
        }
    });
})();
</script>

