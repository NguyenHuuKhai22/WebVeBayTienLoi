<!DOCTYPE html>
<html>
<head>
    <title>Test ChuyenBay</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Test ChuyenBay API</h1>
    
    <button onclick="testAPI()">Test API</button>
    <button onclick="addChuyenBay()">Thêm Chuyến Bay Test</button>
    
    <div id="result" style="margin-top: 20px; white-space: pre-wrap;"></div>

    <script>
        function testAPI() {
            $.ajax({
                url: '{{ route("chuyenbay.test") }}',
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#result').text(JSON.stringify(response, null, 2));
                },
                error: function(xhr) {
                    $('#result').text('Error: ' + JSON.stringify(xhr.responseJSON, null, 2));
                }
            });
        }

        function addChuyenBay() {
            const data = {
                ma_chuyen_bay: "CB" + Math.floor(Math.random() * 1000),
                diem_di: "Hà Nội",
                diem_den: "TP.HCM",
                ngay_gio_khoi_hanh: "2024-03-25 10:00:00",
                ngay_gio_den: "2024-03-25 12:00:00",
                gia_ve_co_ban: 1000000,
                so_ghe_trong: 180,
                id_hang_bay: 1
            };

            $.ajax({
                url: '{{ route("chuyenbay.store") }}',
                method: 'POST',
                data: data,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#result').text(JSON.stringify(response, null, 2));
                },
                error: function(xhr) {
                    $('#result').text('Error: ' + JSON.stringify(xhr.responseJSON, null, 2));
                }
            });
        }
    </script>
</body>
</html> 