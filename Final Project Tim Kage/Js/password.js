// Fungsi validasi password di sisi klien
function validatePassword() {
    var password = document.getElementById('new_password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var errorMessage = document.getElementById('error_message');
    
    // Validasi password (min 8 karakter, kombinasi huruf dan angka)
    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

    if (!passwordRegex.test(password)) {
        errorMessage.innerHTML = "Password harus minimal 8 karakter dan mengandung kombinasi huruf dan angka.";
        return false;
    }

    if (password !== confirmPassword) {
        errorMessage.innerHTML = "Password dan konfirmasi password tidak cocok.";
        return false;
    }

    errorMessage.innerHTML = "";
    return true;
}