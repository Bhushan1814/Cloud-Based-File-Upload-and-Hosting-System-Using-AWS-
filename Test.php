<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Aws\S3\S3Client;

// Create S3 client
$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'eu-north-1'
]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["anyfile"]) && $_FILES["anyfile"]["error"] == 0) {

        // Rename file (avoid duplicate)
        $originalName = $_FILES["anyfile"]["name"];
        $filename = time() . "_" . $originalName;

        $targetPath = "uploads/" . $filename;

        // Move file to uploads folder
        if (move_uploaded_file($_FILES["anyfile"]["tmp_name"], $targetPath)) {

            echo "File moved successfully to: " . $targetPath . "<br>";

            // Upload to S3
            $bucket = 'bhushan0508';
            $file_Path = _DIR_ . '/' . $targetPath;
            $key = basename($file_Path);

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key'    => $key,
                    'Body'   => fopen($file_Path, 'r'),
                    'ACL'    => 'public-read'
                ]);

                echo "Image uploaded successfully.<br>";

                // S3 URL
                $urls3 = $result->get('ObjectURL');

                // CloudFront URL
                $cfurl = str_replace(
                    "https://bhushan0508.s3.eu-east-1.amazonaws.com",
                    "https://d2ro12p9aszxku.cloudfront.net",
                    $urls3
                );

                echo "S3 URL: " . $urls3 . "<br>";
                echo "CloudFront URL: " . $cfurl . "<br>";

                // -------- DATABASE PART --------

                $name = $_POST["name"];

                $servername = "database-1.cx2yecmgowiq.eu-north-1.rds.amazonaws.com";
                $username = "admin";
                $password = "admin$123";
                $dbname = "mydb";

                // Connect DB
                $conn = mysqli_connect($servername, $username, $password, $dbname);

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // IMPORTANT: urls3 column (not s3url)
                $sql = "INSERT INTO posts(name,urls3,cfurl) VALUES('$name','$urls3','$cfurl')";

                if (mysqli_query($conn, $sql)) {
                    echo "Data inserted successfully";
                } else {
                    echo "DB Error: " . mysqli_error($conn);
                }

                mysqli_close($conn);

            } catch (Exception $e) {
                echo "S3 Upload Error: " . $e->getMessage();
            }

        } else {
            echo "File upload failed!";
        }

    } else {
        echo "No file selected or upload error!";
    }
}
?>