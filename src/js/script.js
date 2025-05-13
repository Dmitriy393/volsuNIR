document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('products-container');
    const loadingIndicator = document.getElementById('loading');
    let products = [];
    let currentIndex = 0;
    const batchSize = 5;
    let isLoading = false;

    // 1. Загрузка всех товаров
    async function loadAllProducts() {
        try {
            loadingIndicator.style.display = 'block';
            const response = await fetch('/api/products.php');
            
            if (!response.ok) throw new Error('Network error');
            
            const data = await response.json();
            
            if (data.status !== 'success') throw new Error('API error');
            
            products = data.data;
            loadNextBatch();
            
        } catch (error) {
            console.error('Failed to load products:', error);
            loadingIndicator.textContent = 'Ошибка загрузки';
        } finally {
            loadingIndicator.style.display = 'none';
        }
    }

    // 2. Подгрузка следующей партии
    function loadNextBatch() {
        if (isLoading || products.length === 0) return;
        
        isLoading = true;
        
        // Имитация задержки (можно убрать)
        setTimeout(() => {
            for (let i = 0; i < batchSize; i++) {
                if (currentIndex >= products.length) currentIndex = 0; // Циклическое повторение
                
                const product = products[currentIndex];
                const productElement = createProductElement(product);
                container.appendChild(productElement);
                
                currentIndex++;
            }
            
            isLoading = false;
        }, 300);
    }

    // 3. Создание элемента товара
    function createProductElement(product) {
        const div = document.createElement('div');
        div.className = 'product-card';
        div.innerHTML = `
            <img src="/images/${product.Image}" alt="${product.Name}" 
                 onerror="this.src='/images/placeholder.jpg'">
            <h3>${product.Name}</h3>
            <p class="price">${product.Price.toFixed(2)} руб.</p>
            <p class="description">${product.Description || ''}</p>
        `;
        return div;
    }

    // 4. Обработчик скролла с троттлингом
    let isThrottled = false;
    window.addEventListener('scroll', () => {
        if (isThrottled) return;
        
        isThrottled = true;
        
        const scrollPosition = window.innerHeight + window.scrollY;
        const pageHeight = document.body.offsetHeight - 100;
        
        if (scrollPosition >= pageHeight) {
            loadNextBatch();
        }
        
        setTimeout(() => { isThrottled = false; }, 200);
    });

    // Инициализация
    loadAllProducts();
});