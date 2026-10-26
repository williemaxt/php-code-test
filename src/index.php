<?php

include_once __DIR__ . '/../data/db_connection.php';
include_once __DIR__ . '/Categorizer.php';

$pdo = getDatabaseConnection();

// select all orders from the database
$stmt = $pdo->query('SELECT orderid, comments, shipdate_expected FROM sweetwater_test ORDER BY orderid ASC');
$orders = $stmt->fetchAll();

// categorize the orders based on their comments
$categorizer = new CommentCategorizer();
$categories = $categorizer->categorize($orders);
echo count($categories[CommentCategorizer::REFERRAL]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweetwater Customer Comments Report</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }
        header {
            margin-bottom: 2rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1rem;
        }
        h1 {
            margin: 0 0 0.5rem 0;
            color: #0f172a;
        }
        .stats-bar {
            font-size: 0.95rem;
            color: #64748b;
        }
        .badge {
            background: #e2e8f0;
            color: #334155;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .order-id {
            font-family: monospace;
            font-weight: bold;
            color: #2563eb;
        }
        .ship-date {
            font-family: monospace;
            font-size: 0.9rem;
            color: #0f172a;
            white-space: nowrap;
        }
        .comment-cell {
            white-space: pre-wrap;
            line-height: 1.4;
            font-size: 0.95rem;
        }
        .empty-notice {
            color: #94a3b8;
            font-style: italic;
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Customer Comments Report</h1>
            <div class="stats-bar">
                Total Orders Processed: <strong><?= count($orders); ?></strong>
            </div>
        </header>

        <main>
            <section class="order-section">
                <h2 class="">
                    <span class="badge"><?= count($orders); ?></span>
                </h2>

                <?php if (empty($orders)): ?>
                    <div class="empty-notice">No comments matched this section.</div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 15%;">Order ID</th>
                                <th style="width: 20%;">Ship Date Expected</th>
                                <th style="width: 65%;">Customer Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="order-id">#<?= htmlspecialchars((string)$order['orderid']); ?></td>
                                    <td class="ship-date">
                                        <?= htmlspecialchars((string)($order['shipdate_expected'] ?? 'None')); ?>
                                    </td>
                                    <td class="comment-cell"><?= htmlspecialchars($order['comments']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>