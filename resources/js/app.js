import './bootstrap';

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    // Lấy id của tab đang được chọn
    var targetTab = $(e.target).attr("href");

    // Xử lý để tải dữ liệu từ API hoặc nội dung động cho từng tab khi nó được chọn
    if (targetTab === '#hotels-tab-pane') {
        // Gọi fetch để tải dữ liệu cho tab Hotels
        fetchHotels(); 
    }
    else if (targetTab === '#users-tab-pane') {
        // Gọi fetch để tải dữ liệu cho tab Users
        fetchUsers();
    }
    // Thêm các điều kiện khác cho các tab khác nếu cần
});
