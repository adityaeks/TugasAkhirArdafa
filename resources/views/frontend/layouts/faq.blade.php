<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Frequently Asked Questions</h2>
            <div class="w-24 h-1 bg-blue-500 mx-auto mt-4"></div>
        </div>

        <div class="max-w-3xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="mb-6">
                <button class="flex justify-between items-center w-full text-left bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition duration-300" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-800">Berapa minimal pemesanan untuk layanan prasmanan buffet?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden mt-2 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Minimal pemesanan untuk layanan prasmanan buffet adalah 50 pax. Namun, untuk acara khusus seperti pernikahan, minimal pemesanan adalah 100 pax.</p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="mb-6">
                <button class="flex justify-between items-center w-full text-left bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition duration-300" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-800">Berapa lama waktu pemesanan yang dibutuhkan?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden mt-2 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Kami menyarankan untuk melakukan pemesanan minimal 1 minggu sebelum acara. Untuk acara besar seperti pernikahan, sebaiknya melakukan pemesanan 1-2 bulan sebelumnya.</p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="mb-6">
                <button class="flex justify-between items-center w-full text-left bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition duration-300" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-800">Apakah tersedia layanan setup dan dekorasi?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden mt-2 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Ya, kami menyediakan layanan setup meja dan dekorasi dasar. Untuk dekorasi yang lebih spesifik, dapat didiskusikan dengan tim kami dan mungkin akan dikenakan biaya tambahan.</p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="mb-6">
                <button class="flex justify-between items-center w-full text-left bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition duration-300" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-800">Bagaimana dengan pembayaran dan pembatalan?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden mt-2 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Pembayaran dilakukan dengan DP 50% saat pemesanan dan pelunasan 50% H-1 acara. Pembatalan dapat dilakukan maksimal 3 hari sebelum acara dengan pengembalian dana 50% dari DP.</p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="mb-6">
                <button class="flex justify-between items-center w-full text-left bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition duration-300" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-800">Apakah menu bisa disesuaikan dengan kebutuhan?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden mt-2 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Ya, menu dapat disesuaikan dengan kebutuhan Anda. Kami menyediakan berbagai pilihan menu yang dapat dikombinasikan sesuai dengan tema acara dan budget Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleFAQ(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('svg');

            // Toggle content
            content.classList.toggle('hidden');

            // Rotate icon
            icon.classList.toggle('rotate-180');
        }
</script>