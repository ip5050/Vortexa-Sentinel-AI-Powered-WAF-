<?php
require_once 'config.php';
$logs = file_exists(LOG_FILE) ? array_reverse(file(LOG_FILE)) : [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Vortexa Sentinel Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0b0e14; color: #c9d1d9; font-family: 'Segoe UI', sans-serif; }
        .log-card { background: #161b22; border: 1px solid #30363d; border-radius: 10px; padding: 20px; }
        .table { color: #c9d1d9; border-color: #30363d; }
        .badge-threat { background: #f85149; color: white; }
    </style>
</head>
<body class="p-5">
    <div class="container">
        <h2 class="mb-4 text-primary"><i class="fas fa-shield-virus"></i> Vortexa Sentinel Logs</h2>
        <div class="log-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>IP المهاجم</th>
                        <th>نوع التهديد</th>
                        <th>المسار</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): 
                        $d = explode(' | ', $log);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($d[0]); ?></td>
                        <td><span class="text-info"><?php echo htmlspecialchars($d[1]); ?></span></td>
                        <td><span class="badge badge-threat"><?php echo htmlspecialchars($d[2]); ?></span></td>
                        <td><code><?php echo htmlspecialchars($d[3]); ?></code></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

