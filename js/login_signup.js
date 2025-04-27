document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.wrapper');
    const loginLink = document.querySelector('.register-link');
    const registerLink = document.querySelector('.login-link');
    const loginForm = document.querySelector('.form-box.login form');
    const registerForm = document.querySelector('.form-box.register form');
    const apiUrl = '../login_signup/login_signup_handler.php';

    console.log('Hệ thống đăng nhập/đăng ký đã sẵn sàng');

    loginLink.addEventListener('click', (e) => {
        e.preventDefault();
        wrapper.classList.add('active');
        loginForm.reset();
        console.log('Đã chuyển sang form đăng ký');
    });

    registerLink.addEventListener('click', (e) => {
        e.preventDefault();
        wrapper.classList.remove('active');
        registerForm.reset();
        console.log('Đã chuyển sang form đăng nhập');
    });

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Đang xử lý đăng nhập...');

        const formData = new FormData(this);
        formData.append('action', 'login');

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            console.log('Phản hồi từ server:', result);

            if (result.success) {
                alert(result.message);
                window.location.href = result.redirect;
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Lỗi khi đăng nhập:', error);
            alert('Đã xảy ra lỗi kết nối!');
        }
    });

    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Đang xử lý đăng ký...');

        const password = this.querySelector('[name="password"]').value;
        const confirmPassword = this.querySelector('[name="confirm_password"]').value;
        const email = this.querySelector('[name="email"]').value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Email không hợp lệ!');
            return;
        }

        if (password !== confirmPassword) {
            alert('Xác nhận mật khẩu không khớp!');
            return;
        }

        if (password.length < 6) {
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            return;
        }

        const formData = new FormData(this);
        formData.append('action', 'register');

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            console.log('Phản hồi từ server:', result);

            if (result.success) {
                alert(result.message);
                wrapper.classList.remove('active'); 
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Lỗi khi đăng ký:', error);
            alert('Đã xảy ra lỗi kết nối!');
        }
    });
});
