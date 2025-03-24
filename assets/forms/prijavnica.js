// Note: This is a simple password check for testing purposes only.
// In a production environment, use server-side authentication.
document.getElementById('login-link').addEventListener('click', function(e) {
    e.preventDefault();
    var password = prompt('Enter password:');
    if (password === '515151') {
        window.location.href = 'edu/';
    } else {
        alert('Incorrect password');
    }
});