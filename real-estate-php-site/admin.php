<?php
require __DIR__ . '/includes/functions.php';

// Load contacts from JSON file
$file = __DIR__ . '/data/contacts.json';
$contacts = [];

try {
    if (file_exists($file) && is_readable($file)) {
        $jsonData = file_get_contents($file);
        $contacts = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON in contacts.json: ' . json_last_error_msg());
        }
    }
} catch (Exception $e) {
    error_log('Error in admin.php: ' . $e->getMessage());
    $contacts = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Leads - Rudraa Housing India</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #008080;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f1f5f9;
        }
        .alert {
            background: #fef2f2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <div class="container table-container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-teal-800 mb-6">Admin: Leads</h1>
        <?php if (empty($contacts)): ?>
            <div class="alert">No leads yet.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Property ID</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $c): ?>
                        <tr>
                            <td><?php echo (int)($c['id'] ?? 0); ?></td>
                            <td><?php echo htmlspecialchars($c['name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($c['email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($c['phone'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($c['message'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($c['property_id'] ?? 'N/A'); ?></td>
                            <td><?php 
                                $created_at = $c['created_at'] ?? 'N/A';
                                if ($created_at !== 'N/A' && strtotime($created_at)) {
                                    echo htmlspecialchars(date('d M Y, H:i', strtotime($created_at)));
                                } else {
                                    echo 'N/A';
                                }
                            ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>