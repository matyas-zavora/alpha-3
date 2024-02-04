<?php
// This file is supposed to be called from the download button in the read.php file.
// It will download the full parcel data as a CSV file.
// The file will be named "full_parcel_data.csv".
include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_data = "SELECT 
                    p.number,
                    p.size,
                    p.latitude,
                    p.longitude,
                    p.date_of_ownership,
                    p.legal_state,
                    p.type,
                    p.address,
                    p.zip,
                    p.city,
                    p.country,
                    ol.stake,
                    ow.first_name,
                    ow.last_name,
                    ow.phone,
                    c.name AS company_name,
                    c.address AS company_address,
                    c.zip AS company_zip,
                    c.city AS company_city,
                    c.country AS company_country
                 FROM parcel p 
                 LEFT JOIN ownership_list ol on ol.id_parcel = p.id 
                 LEFT JOIN owner ow on ol.id_owner = ow.id 
                 LEFT JOIN company c on ol.id_company = c.id;";

    $data = $conn->query($sql_data);
    $conn->close();

    $file = fopen("../output/full_parcel_data.csv", "w");

    // Write the header to the CSV file
    fputcsv($file, array_keys($data->fetch_assoc()));

    // Reset the data pointer back to the beginning
    $data->data_seek(0);

    while ($row = $data->fetch_assoc()) {
        // Exclude the ID columns
        unset($row['id'], $row['id_parcel'], $row['id_owner'], $row['id_company']);

        // Write the row to the CSV file
        fputcsv($file, $row);
    }

    // Close the file pointer
    fclose($file);

    // Optionally, you can force download the file
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=full_parcel_data.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile("../output/full_parcel_data.csv");
    exit;
}