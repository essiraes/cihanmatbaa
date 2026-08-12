document.addEventListener("DOMContentLoaded", () => {
    
    // --- 1. MOBİL MENÜ İŞLEVİ ---
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    // Hamburger ikona tıklanınca menüyü aç/kapat
    if(mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Mobilde bir linke tıklanınca menüyü otomatik kapat
    const mobileLinks = document.querySelectorAll('.mobile-nav-links a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });

    // --- 2. HİZMETLER ÜRÜN FİLTRELEME SİSTEMİ ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productItems = document.querySelectorAll('.product-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            
            // Aktif buton stilini tüm butonlardan temizle
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-appleDark', 'text-white');
                b.classList.add('bg-white', 'text-gray-600'); // Pasif renkleri geri ver
            });
            
            // Tıklanan butona aktif stillerini ekle
            btn.classList.add('active', 'bg-appleDark', 'text-white');
            btn.classList.remove('bg-white', 'text-gray-600');

            // Hangi kategoriye tıklandığını al
            const filterValue = btn.getAttribute('data-filter');

            // Ürünleri filtrele ve animasyonu yeniden tetikle
            productItems.forEach(item => {
                // Önce tüm öğeleri gizle
                item.style.display = 'none'; 
                
                // Kategorisi uyuşanları veya "all" seçilenleri göster
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    // CSS animasyonunun (fadeIn) pürüzsüz çalışması için küçük bir gecikme
                    setTimeout(() => {
                        item.style.display = 'block';
                    }, 50);
                }
            });
        });
    });

    // --- 3. SCROLL (AŞAĞI KAYDIRDIKÇA BELİRME) ANİMASYONLARI ---
    const revealElements = document.querySelectorAll('.reveal');
    const revealOptions = { 
        root: null, 
        threshold: 0.15, // Elementin %15'i göründüğünde başla
        rootMargin: "0px 0px -50px 0px" 
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add('active'); // active sınıfı CSS'teki animasyonu başlatır
                observer.unobserve(entry.target); // Animasyon sadece bir kez çalışsın diye izlemeyi bırakır
            }
        });
    }, revealOptions);

    revealElements.forEach(el => revealObserver.observe(el));
});