<?php
// API Xử lý thuật toán MD5 Tài Xỉu
// GIỮ NGUYÊN THUẬT TOÁN NHƯ CODE GỐC

// Khởi tạo session để lưu thống kê
session_start();
if (!isset($_SESSION['analysis_count'])) {
    $_SESSION['analysis_count'] = 0;
    $_SESSION['win_count'] = 0;
}

// Cấu hình
header('Content-Type: application/json');

// Key bảo mật - PHẢI GIỐNG VỚI KEY TRONG index.php
$SECRET_KEY = "ADMIN2025@K_PROXTON_ABCXYZ_1026";

// Kiểm phương thức
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Phương thức không hợp lệ']);
    exit;
}

// Lấy dữ liệu
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$md5Input = isset($input['md5']) ? trim($input['md5']) : '';
$userKey = isset($input['key']) ? trim($input['key']) : '';

// 1. Kiểm tra Key - GIỮ NGUYÊN NHƯ CODE GỐC
if ($userKey !== $SECRET_KEY) {
    echo json_encode(['error' => 'SAI KEY KÍCH HOẠT! Vui lòng liên hệ Admin để nhận mã.']);
    exit;
}

// 2. Kiểm tra MD5 - GIỮ NGUYÊN NHƯ CODE GỐC
if (!$md5Input) {
    echo json_encode(['error' => 'Vui lòng nhập mã MD5']);
    exit;
}

if (strlen($md5Input) !== 32 || !preg_match('/^[a-fA-F0-9]{32}$/', $md5Input)) {
    echo json_encode(['error' => 'Mã MD5 không hợp lệ (phải đủ 32 ký tự)!']);
    exit;
}

// THUẬT TOÁN PHÂN TÍCH - GIỮ NGUYÊN 100% NHƯ CODE GỐC
function generateTaiXiuPrediction($md5Hash) {
    // Tăng số lần phân tích
    $_SESSION['analysis_count']++;
    
    $lastChar = strtolower($md5Hash[strlen($md5Hash) - 1]);
    $isTai = intval($lastChar, 16) >= 8; 
    $confidence = rand(79, 99); // 79-99% - GIỮ NGUYÊN
    
    // Tăng số thắng - GIỮ NGUYÊN logic code gốc
    if (rand(1, 100) > 20) { // 80% cơ hội thắng
        $_SESSION['win_count']++;
    }
    
    $winRate = $_SESSION['analysis_count'] > 0 
        ? round(($_SESSION['win_count'] / $_SESSION['analysis_count']) * 100)
        : 0;
    
    $luckyNumber = intval(substr($md5Hash, 0, 2), 16) % 100;
    
    // Xác định kết quả - GIỮ NGUYÊN logic code gốc
    if ($isTai) {
        $result = "XỈU";
        $advice = "NÊN VÀO XỈU";
        $resultClass = "tai-text";
        $adviceColor = "#008000";
    } else {
        $result = "TÀI";
        $advice = "NÊN VÀO TÀI";
        $resultClass = "xiu-text";
        $adviceColor = "#ff0000";
    }
    
    return [
        'result' => $result,
        'confidence' => $confidence,
        'lucky_number' => $luckyNumber,
        'advice' => $advice,
        'result_class' => $resultClass,
        'advice_color' => $adviceColor,
        'analysis_count' => $_SESSION['analysis_count'],
        'win_count' => $_SESSION['win_count'],
        'win_rate' => $winRate,
        'md5_provided' => $md5Hash,
        'last_char' => $lastChar,
        'last_char_value' => intval($lastChar, 16),
        'algorithm_version' => 'MD5 Tai Xiu Algorithm v1.0 (Original)'
    ];
}

// Thực hiện phân tích
$result = generateTaiXiuPrediction($md5Input);

// Trả về kết quả
echo json_encode([
    'success' => true,
    'data' => $result,
    'timestamp' => date('Y-m-d H:i:s'),
    'message' => 'Phân tích thành công!'
]);
?>