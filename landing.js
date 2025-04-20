document.addEventListener("DOMContentLoaded", function () {
    // Example book data (replace with dynamic data)
    const books = [
        { id:1, title: "The Alchemist", author: "Paulo Coelho", price: "₹259.00"},
        { id:2, title: "When Breath Becomes Air", author: "Paul Kalanithi", price: "₹199.00" },
        { id:3, title: "The Adventures of Tom Sawyer", author: "Mark Twain", price: "₹149.00" },
        { id:4, title: "WINGS OF FIRE", author: "APJ Abdul Kalam", price: "₹174.00" },
        { id:5, title: "The Power of Your Subconscious Mind", author: "Joseph Murphy", price: "₹129.00" },
        { id:6, title: "Ghosts of The Silent Hills", author: " Anita Krishan ", price: "₹169.00" },
        { id:7, title: "The Diary Of A Young Girl -FINGERPRINT ", author: "Anne Frank ", price: "₹119.00" },
        { id:8, title: "KARMA: A YOGI'S GUIDE TO CRAFTING YOUR DESTINY", author: "Sadhguru", price: "₹192.00" },
        { id:9, title: "Always On My Mind", author: " Beth Moran ", price: "₹149.00" },
        { id:10, title: "You Only Live Once", author: "Changle Stuti", price: "₹165.00" },
        { id:11, title: "Be Your Own Sunshine", author: " James Allen", price: "₹115.00" },
        { id:12, title: "Don't Believe Everything You Think ", author: "Joseph Nguyen", price: "₹191.00" },
        { id:13, title: "Don't Believe Everything You Think ", author: "Joseph Nguyen", price: "₹191.00" },
        { id:14, title: "Don't Believe Everything You Think ", author: "Joseph Nguyen", price: "₹191.00" },
        { id:15, title: "Don't Believe Everything You Think ", author: "Joseph Nguyen", price: "₹191.00" },
    ];
     // Function to display books
     function displayBooks() {
        const booksContainer = document.querySelector(".books");

        books.forEach((book) => {
            const bookCard = document.createElement("div");
            bookCard.classList.add("book-card");
            bookCard.innerHTML = `
                <h2>${book.title}</h2>
                <p>Author: ${book.author}</p>
                <p>Price: ${book.price}</p>
                <button onclick="addToCart(${book.id} , '${book.title}', '${book.author}', ${book.price})">Add Cart</button>
                <button>Buy Now</button>
            `;
            booksContainer.appendChild(bookCard);
        });
    }

    function addToCart(id, price) {
      const cart = JSON.parse(localStorage.getItem("cart")) || [];
      const existingItem = cart.find(item => book.id === id);
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({ id, title, price, quantity: 1 });
      }
      localStorage.setItem("cart", JSON.stringify(cart));
      alert(`${author} added to cart!`);
    }

     // Function to display the signup form

        const form = document.getElementById('signupForm');

        form.addEventListener('submit', (event) => {
            let isValid = true;

            // Clear previous errors
            document.getElementById('usernameError').textContent = '';
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';

            // Validate username
            const username = document.getElementById('username').value;
            if (username.length < 3) {
                isValid = false;
                document.getElementById('usernameError').textContent = 'Username must be at least 3 characters long.';
            }

            // Validate email
            const email = document.getElementById('email').value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                isValid = false;
                document.getElementById('emailError').textContent = 'Enter a valid email address.';
            }

            // Validate password
            const password = document.getElementById('password').value;
            if (password.length < 6) {
                isValid = false;
                document.getElementById('passwordError').textContent = 'Password must be at least 6 characters long.';
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });

    // Function to display the login form
    function displayLoginForm() {
        const loginSection = document.querySelector(".login");
        loginSection.innerHTML = `
            <h2>Log In</h2>
            <!-- Add your login form here -->
        `;
    }

    // Initial page load
    displayBooks();
    displaySignupForm();
    displayLoginForm();
});
