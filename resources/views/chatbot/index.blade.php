
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl overflow-hidden shadow-xl">
        <div class="bg-red-600 px-6 py-5">
            <h1 class="text-2xl font-bold text-white">
                CineBook AI
            </h1>
            <p class="text-red-100 text-sm mt-1">
                Find your next movie based on your mood.
            </p>
        </div>

        <div id="chatMessages"
             class="h-[450px] overflow-y-auto p-5 space-y-4 bg-slate-950">
            <div class="bg-slate-800 text-slate-100 rounded-xl p-4 max-w-[85%]">
                Hey! 👋 I'm CineBook AI. Tell me your mood or what kind of movie
                you'd like to watch, and I'll suggest movies from our collection.
            </div>
        </div>

        <form id="chatForm" class="p-4 bg-slate-900 border-t border-slate-700">
            @csrf

            <div class="flex gap-3">
                <input
                    id="chatInput"
                    type="text"
                    maxlength="1000"
                    placeholder="e.g. Recommend a feel-good comedy..."
                    class="flex-1 min-w-0 rounded-xl bg-slate-800 border border-slate-700 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500"
                    required
                >

                <button
                    id="sendButton"
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white font-semibold rounded-xl px-5 py-3"
                >
                    Send
                </button>
            </div>

            <p id="chatError" class="text-red-400 text-sm mt-2 hidden"></p>
        </form>
    </div>
</div>

<script>
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');
    const sendButton = document.getElementById('sendButton');
    const chatError = document.getElementById('chatError');

    function addMessage(text, sender) {
        const message = document.createElement('div');

        message.className = sender === 'user'
            ? 'ml-auto max-w-[85%] rounded-xl bg-red-600 p-4 text-white whitespace-pre-wrap'
            : 'max-w-[85%] rounded-xl bg-slate-800 p-4 text-slate-100 whitespace-pre-wrap';

        // Use textContent so AI output is displayed safely as text.
        message.textContent = text;

        chatMessages.appendChild(message);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    chatForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const message = chatInput.value.trim();

        if (!message) return;

        chatError.textContent = '';
        chatError.classList.add('hidden');

        addMessage(message, 'user');

        chatInput.value = '';
        chatInput.disabled = true;
        sendButton.disabled = true;
        sendButton.textContent = 'Thinking...';

        const loadingMessage = document.createElement('div');
        loadingMessage.className =
            'max-w-[85%] rounded-xl bg-slate-800 p-4 text-slate-300';
        loadingMessage.textContent = 'Finding a movie for you...';
        chatMessages.appendChild(loadingMessage);

        try {
            const response = await fetch(@json(route('chatbot.chat')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': chatForm.querySelector(
                        'input[name="_token"]'
                    ).value
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            loadingMessage.remove();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Something went wrong. Please try again.'
                );
            }

            addMessage(data.reply, 'assistant');
        } catch (error) {
            loadingMessage.remove();
            chatError.textContent = error.message;
            chatError.classList.remove('hidden');
        } finally {
            chatInput.disabled = false;
            sendButton.disabled = false;
            sendButton.textContent = 'Send';
            chatInput.focus();
        }
    });
</script>
@endsection
