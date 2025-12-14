<footer>
    <div class="container footer-container">
        <div class="footer-content">
            <!-- Brand Column -->
            <div class="footer-section brand-section">
                <h3>FLEXSPORT</h3>
                <p>The premier destination for professional sports equipment. Elevate your performance with our curated selection of high-end gear.</p>
                <div class="social-links">
                    <a href="#" class="social-icon" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                    </a>
                </div>
            </div>
            
            <!-- Shop Column -->
            <div class="footer-section">
                <h4>SHOP</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('collection') }}?sort=new">New Arrivals</a></li>
                    <li><a href="{{ route('collection') }}?sort=popular">Best Sellers</a></li>
                    <li><a href="{{ route('collection') }}?category=mens">Men's Equipment</a></li>
                    <li><a href="{{ route('collection') }}?category=womens">Women's Equipment</a></li>
                    <li><a href="{{ route('collection') }}?sort=sale">Sale</a></li>
                </ul>
            </div>

            <!-- Support Column -->
            <div class="footer-section">
                <h4>SUPPORT</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('page', 'faq') }}">FAQ</a></li>
                    <li><a href="{{ route('page', 'contact') }}">Order Tracking</a></li>
                    <li><a href="{{ route('page', 'shipping') }}">Shipping Info</a></li>
                    <li><a href="{{ route('page', 'returns') }}">Returns & Exchanges</a></li>
                    <li><a href="{{ route('page', 'contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <!-- Company/Admin Column -->
            <div class="footer-section">
                <h4>COMPANY</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('page', 'about') }}">About Us</a></li>
                    <li><a href="{{ route('page', 'terms') }}">Terms of Service</a></li>
                    <li><a href="{{ route('page', 'privacy') }}">Privacy Policy</a></li>
                </ul>
                
                <div class="admin-access" style="margin-top: 2rem;">
                    <a href="{{ route('admin.dashboard') }}" class="btn-admin-access">
                        <span class="lock-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></span> Staff / Admin Access
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="copyright">
                &copy; {{ date('Y') }} FlexSport. All rights reserved. Made with <span style="color:var(--accent); display:inline-flex; vertical-align:middle;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></span> for Sport Lovers.
            </div>
            <div class="payment-methods">
                <!-- Payment icons could go here -->
                <span>Secure Payment</span>
            </div>
        </div>
    </div>
</footer>