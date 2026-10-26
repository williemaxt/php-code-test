<?php
declare(strict_types=1);

class ShipDateUpdater
{
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function updateExpectedShipDates() {
        // select orders that mention Expected Ship Date
        $sql = "SELECT orderid, comments FROM sweetwater_test WHERE comments LIKE '%Expected Ship Date%'";
        $stmt = $this->pdo->query($sql);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $updateStmt = $this->pdo->prepare("
            UPDATE sweetwater_test 
            SET shipdate_expected = :shipdate_expected 
            WHERE orderid = :orderid
        ");

        foreach ($records as $row) {
            // match "Expected Ship Date with regex
            if (preg_match('/Expected Ship Date:\s*([0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4})/i', $row['comments'], $matches)) {
                
                $rawDate = trim($matches[1]);
                $parsedDate = DateTime::createFromFormat('m/d/y', $rawDate);

                // if a date is successfully parsed, format it to 'Y-m-d 00:00:00' and update the database
                if ($parsedDate) {
                    $formattedDate = $parsedDate->format('Y-m-d 00:00:00');
                    $updateStmt->execute([
                        ':shipdate_expected' => $formattedDate,
                        ':orderid'  => $row['orderid']
                    ]);
                }
            }
        }
    }
}