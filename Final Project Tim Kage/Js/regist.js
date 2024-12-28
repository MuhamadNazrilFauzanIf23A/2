function validateForm() {
    const emailOrPhone = document.getElementById("email_or_phone").value;
    const password = document.querySelector('input[name="password"]').value;
    const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    const phoneRegex = /^\d{10,12}$/;

    if (!emailRegex.test(emailOrPhone) && !phoneRegex.test(emailOrPhone)) {
        alert("Input harus berupa email @gmail.com atau nomor HP 10-12 angka!");
        return false;
    }

    if (password.length < 8) {
        alert("Password harus memiliki minimal 8 karakter!");
        return false;
    }

    return true;
}
