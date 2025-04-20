// Authentication utility functions
function checkAuth() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
        // Store the current page URL to redirect back after login
        localStorage.setItem('redirectAfterLogin', window.location.href);
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

function requireAuth() {
    if (!checkAuth()) {
        return false;
    }
    return true;
}

function logout() {
    localStorage.removeItem('currentUser');
    localStorage.removeItem('cart'); // Clear cart on logout
    window.location.href = 'login.html';
}

// Function to protect all website features
function protectWebsite() {
    // Protect all navigation links except login and register
    document.querySelectorAll('.nav-links a').forEach(link => {
        if (!link.href.includes('login.html') && !link.href.includes('register.html')) {
            link.onclick = function(e) {
                e.preventDefault();
                if (!requireAuth()) {
                    return;
                }
                window.location.href = this.href;
            };
        }
    });

    // Protect all buttons that perform actions
    document.querySelectorAll('button').forEach(button => {
        if (button.id !== 'logoutBtn') {
            const originalOnClick = button.onclick;
            button.onclick = function(e) {
                e.preventDefault();
                if (!requireAuth()) {
                    return;
                }
                if (originalOnClick) {
                    originalOnClick.call(this, e);
                }
            };
        }
    });

    // Protect all forms
    document.querySelectorAll('form').forEach(form => {
        if (!form.id.includes('loginForm') && !form.id.includes('registerForm')) {
            const originalSubmit = form.onsubmit;
            form.onsubmit = function(e) {
                e.preventDefault();
                if (!requireAuth()) {
                    return false;
                }
                if (originalSubmit) {
                    return originalSubmit.call(this, e);
                }
                return true;
            };
        }
    });

    // Protect all links that perform actions
    document.querySelectorAll('a').forEach(link => {
        if (!link.href.includes('login.html') && !link.href.includes('register.html')) {
            const originalOnClick = link.onclick;
            link.onclick = function(e) {
                e.preventDefault();
                if (!requireAuth()) {
                    return;
                }
                if (originalOnClick) {
                    originalOnClick.call(this, e);
                } else {
                    window.location.href = this.href;
                }
            };
        }
    });
}

// Function to update header based on auth status
function updateHeaderAuth() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    const navLinks = document.querySelector('.nav-links');
    
    if (currentUser) {
        // User is logged in
        const loginLink = navLinks.querySelector('a[href="login.html"]');
        if (loginLink) {
            // Create user menu container
            const userMenu = document.createElement('div');
            userMenu.className = 'user-menu';
            userMenu.innerHTML = `
                <a href="#" class="user-profile">
                    <i class="fas fa-user"></i> ${currentUser.name}
                </a>
                <div class="user-dropdown">
                    <a href="#" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            `;
            
            // Replace login link with user menu
            loginLink.replaceWith(userMenu);
            
            // Add styles for user menu
            const style = document.createElement('style');
            style.textContent = `
                .user-menu {
                    position: relative;
                    display: inline-block;
                }
                
                .user-profile {
                    color: white;
                    text-decoration: none;
                    padding: 0.5rem 1rem;
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                
                .user-profile:hover {
                    background-color: rgba(255, 255, 255, 0.1);
                }
                
                .user-dropdown {
                    position: absolute;
                    top: 100%;
                    right: 0;
                    background-color: white;
                    border-radius: 6px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    padding: 0.5rem 0;
                    min-width: 150px;
                    display: none;
                    z-index: 1000;
                }
                
                .user-menu:hover .user-dropdown {
                    display: block;
                }
                
                .user-dropdown a {
                    color: #333;
                    text-decoration: none;
                    padding: 0.5rem 1rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    transition: background-color 0.3s ease;
                }
                
                .user-dropdown a:hover {
                    background-color: #f5f5f5;
                    color: #240955;
                }
                
                .user-dropdown i {
                    width: 20px;
                    text-align: center;
                }
            `;
            document.head.appendChild(style);
        }
    } else {
        // User is not logged in
        const userMenu = navLinks.querySelector('.user-menu');
        if (userMenu) {
            // Replace user menu with login link
            const loginLink = document.createElement('a');
            loginLink.href = 'login.html';
            loginLink.innerHTML = '<i class="fas fa-user"></i> Login';
            userMenu.replaceWith(loginLink);
        }

        // Show login required message
        const mainContent = document.querySelector('main');
        if (mainContent && !window.location.href.includes('login.html') && !window.location.href.includes('register.html')) {
            const loginMessage = document.createElement('div');
            loginMessage.className = 'login-required-message';
            loginMessage.innerHTML = `
                <div class="message-content">
                    <i class="fas fa-lock"></i>
                    <h2>Login Required</h2>
                    <p>Please login to access this feature</p>
                    <a href="login.html" class="login-btn">Login Now</a>
                </div>
            `;
            mainContent.innerHTML = '';
            mainContent.appendChild(loginMessage);

            // Add styles for login required message
            const style = document.createElement('style');
            style.textContent = `
                .login-required-message {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 60vh;
                    text-align: center;
                }

                .message-content {
                    background: white;
                    padding: 2rem;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    max-width: 400px;
                    width: 90%;
                }

                .message-content i {
                    font-size: 3rem;
                    color: #240955;
                    margin-bottom: 1rem;
                }

                .message-content h2 {
                    color: #240955;
                    margin-bottom: 1rem;
                }

                .message-content p {
                    color: #666;
                    margin-bottom: 1.5rem;
                }

                .login-btn {
                    display: inline-block;
                    padding: 0.8rem 1.5rem;
                    background: linear-gradient(45deg, #240955, #4CAF50);
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: bold;
                    transition: all 0.3s ease;
                }

                .login-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                }
            `;
            document.head.appendChild(style);
        }
    }
}

// Function to handle protected actions (like adding to cart)
function handleProtectedAction(callback) {
    if (requireAuth()) {
        callback();
    }
}

// Initialize protection when the page loads
document.addEventListener('DOMContentLoaded', () => {
    protectWebsite();
    updateHeaderAuth();
}); 