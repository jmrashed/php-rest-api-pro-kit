document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.debugbar-tab');
    const contents = document.querySelectorAll('.debugbar-content');
    const toggle = document.getElementById('debugbar-toggle');
    const debugbar = document.getElementById('debugbar');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.dataset.tab;
            
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });

    if (toggle) {
        toggle.addEventListener('click', function() {
            debugbar.style.display = debugbar.style.display === 'none' ? 'block' : 'none';
        });
    }

    // Activate first tab by default
    if (tabs.length > 0) {
        tabs[0].click();
    }
});