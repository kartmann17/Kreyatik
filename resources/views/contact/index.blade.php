<x-header title="Contact" />
<main class="site-content" role="main">
    <section class="contact-section" aria-label="Formulaire de contact">
        <div class="container">
            <h1 class="contact-title text-3xl font-bold">Parlons de votre projet</h1>
            <p class="text-center mb-8 text-gray-600">Remplissez le formulaire ci-dessous et nous vous répondrons rapidement.</p>

            <form class="contact-form bg-white p-6 rounded-lg shadow-md"
                action="{{ route('send.email') }}"
                method="POST"
                aria-label="Formulaire de contact">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-gray-700 mb-2">Nom</label>
                        <input type="text"
                            class="contact-input w-full"
                            id="name"
                            name="name"
                            required
                            aria-required="true"
                            autocomplete="name"
                            placeholder="Votre nom">
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 mb-2">Email</label>
                        <input type="email"
                            class="contact-input w-full"
                            id="email"
                            name="email"
                            required
                            aria-required="true"
                            autocomplete="email"
                            placeholder="votre@email.com">
                    </div>
                </div>
                <div class="mb-6">
                    <label for="subject" class="block text-gray-700 mb-2">Objet</label>
                    <input type="text"
                        class="contact-input w-full"
                        id="subject"
                        name="object_message"
                        required
                        aria-required="true"
                        placeholder="Sujet de votre message">
                </div>
                <div class="mb-6">
                    <label for="message" class="block text-gray-700 mb-2">Message</label>
                    <textarea class="contact-textarea w-full"
                        id="message"
                        name="message"
                        rows="5"
                        required
                        aria-required="true"
                        placeholder="Votre message"
                        aria-label="Contenu du message"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit"
                        class="contact-button"
                        aria-label="Envoyer le message">
                        Envoyer
                    </button>
                </div>
            </form>
            <div class="mt-6" role="alert">
                <div id="error-message"
                    class="hidden bg-red-500 text-white p-4 rounded-md mb-4"
                    aria-live="polite"></div>
                <div id="success-message"
                    class="hidden bg-green-500 text-white p-4 rounded-md"
                    aria-live="polite"></div>
            </div>
        </div>
    </section>
</main>
<x-footer />