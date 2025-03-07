// REGISTER 
function validateRegister() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("confirm_password").value;

    // Điều kiện: tài khoản không được rỗng và có ít nhất 8 ký tự
    if (username.length < 8) {
        alert("Tài khoản phải có ít nhất 8 ký tự.");
        return false;
    }

    // Điều kiện: tài khoản không chứa ký tự đặc biệt (chỉ cho phép chữ và số)
    var usernamePattern = /^[a-zA-Z0-9]+$/;
    if (!usernamePattern.test(username)) {
        alert("Tài khoản chỉ được chứa chữ cái và số.");
        return false;
    }

    // Điều kiện: Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordPattern.test(password)) {
        alert("Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.");
        return false;
    }

    // Điều kiện: Mật khẩu không chứa dấu cách
    if (password.includes(' ')) {
        alert("Mật khẩu không được chứa dấu cách.");
        return false;
    }

    // Điều kiện: Xác nhận mật khẩu phải trùng khớp
    if (password !== confirm_password) {
        alert("Mật khẩu và xác nhận mật khẩu không khớp.");
        return false;
    }

    return true; // Nếu mọi điều kiện hợp lệ
}


// ADD TO CART
$(document).ready(function() {
    // Loại bỏ mọi sự kiện click cũ trước khi đăng ký sự kiện mới
    $(".cart-btn").off("click").on("click", function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của nút

        var book_id = $(this).data("id");
        var count = 1; // Số lượng mặc định là 1

        $.ajax({
            url: 'http://localhost/bookstore/store/add-to-cart.php', // Đường dẫn đúng tới add-to-cart.php
            type: 'POST',
            data: {
                book_id: book_id,
                count: count
            },
            success: function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert(result.message);
                    } else {
                        alert(result.message);
                    }
                } catch (e) {
                    console.error("Lỗi JSON:", e);
                    alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.");
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
            }
        });
    });
});


// ADD TO FAVOURITE 
$(document).ready(function() {
    // Khi người dùng click vào nút yêu thích
    $(".favorite-btn").click(function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của nút

        var book_id = $(this).data("id");

        $.ajax({
            url: 'http://localhost/bookstore/store/add-to-favorites.php', // Đường dẫn đến file xử lý thêm yêu thích
            type: 'POST',
            data: {
                book_id: book_id
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message); // Thông báo thành công
                } else {
                    alert(result.message); // Thông báo lỗi
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi thêm sách vào danh sách yêu thích.');
            }
        });
    });
});

// STORE-BOOK DETAIL
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-book').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
            const bookId = this.getAttribute('data-id');
            fetchBookDetails(bookId);
        });
    });
});

function fetchBookDetails(bookId) {
    console.log(`fetchBookDetails được gọi với bookId: ${bookId}`);
    fetch(`http://localhost/bookstore/store/get-book-detail.php?book_id=${bookId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log('Dữ liệu nhận được:', data); // Ghi lại dữ liệu nhận được
            document.getElementById('book-info').innerHTML = data; // Cập nhật nội dung modal
        })
        .catch(error => {
            console.error('Error fetching book details:', error);
            document.getElementById('book-info').innerHTML = 'Không thể tải thông tin sách.';
        });
}