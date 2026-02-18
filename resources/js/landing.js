/**
 * Lightweight Landing Page JavaScript
 * Pure vanilla JS - No dependencies required
 * Total size: ~1KB (vs 50KB AlpineJS)
 */

// Mobile Menu Toggle
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', function () {
    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    const menu = document.getElementById('mobileMenu');
                    if (menu && !menu.classList.contains('hidden')) {
                        menu.classList.add('hidden');
                    }
                }
            }
        });
    });
});

// Make toggleMobileMenu available globally
window.toggleMobileMenu = toggleMobileMenu;
