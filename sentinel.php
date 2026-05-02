<?php
require_once 'config.php';

function vortexa_sentinel() {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    $payload = json_encode(['GET' => $_GET, 'POST' => $_POST, 'BODY' => file_get_contents('php://input')]);

    // 1. الفحص السريع بالأنماط التقليدية (Regex)
    $patterns = [
        'SQL_Injection' => '/(union\s+select|insert\s+into|--|#)/i',
        'XSS' => '/(<script|alert\(|onerror=)/i',
        'LFI_RFI' => '/(\.\.\/|\/etc\/passwd|http:\/\/)/i'
    ];

    foreach ($patterns as $type => $pattern) {
        if (preg_match($pattern, $payload)) {
            // إذا اكتشف الهجوم بالنمط التقليدي، أرسل بلاغاً فوراً
            handle_threat($type, $user_ip, $uri, "High (Pattern Match)");
        }
    }

    // 2. التحليل المتقدم بالذكاء الاصطناعي (AI Analysis)
    // نرسل الطلبات المشبوهة فقط للـ AI لتقليل استهلاك الـ API
    if (strlen($payload) > 15) { 
        $is_malicious = analyze_with_ai($payload);
        if ($is_malicious) {
            handle_threat("AI_Detected_Anomaly", $user_ip, $uri, "Critical (AI Analysis)");
        }
    }
}

function analyze_with_ai($data) {
    $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
    $prompt = "Analyze this web request data and return only 'SECURE' or 'MALICIOUS'. Data: $data";
    
    $postData = [
        "model" => "llama3-8b-8192",
        "messages" => [["role" => "user", "content" => $prompt]]
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . GROQ_API_KEY, "Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = json_decode(curl_exec($ch), true);
    $result = $response['choices'][0]['message']['content'] ?? 'SECURE';
    curl_close($ch);

    return (trim($result) === 'MALICIOUS');
}

function handle_threat($type, $ip, $uri, $severity) {
    $time = date("Y-m-d H:i:s");
    $msg = "🚨 *Vortexa Sentinel Alert*\n\n🔴 *Threat:* $type\n🔥 *Severity:* $severity\n🌐 *IP:* `$ip`\n🔗 *Path:* `$uri`\n📅 *Time:* $time";

    // إرسال لتليجرام
    $url = "https://api.telegram.org/bot" . TELEGRAM_TOKEN . "/sendMessage?chat_id=" . TELEGRAM_CHAT_ID . "&text=" . urlencode($msg) . "&parse_mode=Markdown";
    file_get_contents($url);

    // تسجيل في الملف
    file_put_contents(LOG_FILE, "[$time] IP: $ip | Type: $type | URI: $uri\n", FILE_APPEND);

    // حجب الوصول
    die("<div style='background:#000;color:red;padding:50px;text-align:center;font-family:sans-serif;'><h1>403 Forbidden</h1><p>Security Threat Detected by Vortexa Sentinel AI.</p></div>");
}

vortexa_sentinel();

