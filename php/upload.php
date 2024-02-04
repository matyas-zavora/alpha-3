<?php
include '../config.php';
include '../templates/upload.html';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "../input/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($fileType != "csv") {
        echo "Sorry, only CSV files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $file = fopen($target_file, "r");
    $header = fgetcsv($file);
    $data = array();
    while ($row = fgetcsv($file)) {
        $data[] = array_combine($header, $row);
    }
    fclose($file);
    unlink($target_file);

    $header_template = array("number", "size", "latitude", "longitude", "date_of_ownership", "legal_state", "type",
        "address", "zip", "city", "country", "stake", "first_name", "last_name", "phone", "company_name",
        "company_address", "company_zip", "company_city", "company_country");
    if ($header != $header_template) {
        echo "Sorry, the header of your file is not correct.";
        exit();
    } else {
        foreach ($data as $row) {
            $parcel_number = $row['number'];
            $sql = "SELECT * FROM parcel WHERE number = '$parcel_number'";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO parcel (number, size, latitude, longitude, date_of_ownership, legal_state, type,
                     address, zip, city, country) VALUES ('" . $row['number'] . "', '" . $row['size'] . "', '" .
                    $row['latitude'] . "', '" . $row['longitude'] . "', '" . $row['date_of_ownership'] . "', '" .
                    $row['legal_state'] . "', '" . $row['type'] . "', '" . $row['address'] . "', '" . $row['zip'] .
                    "', '" . $row['city'] . "', '" . $row['country'] . "')";

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    exit();
                }
            }
        }

        foreach ($data as $row) {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $sql = "SELECT * FROM owner WHERE first_name = '$first_name' AND last_name = '$last_name'";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO owner (first_name, last_name, phone) VALUES ('" . $row['first_name'] . "', '" . $row['last_name'] . "', '" .
                    $row['phone'] . "')";

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    exit();
                }
            }
        }

        foreach ($data as $row) {
            $company_name = $row['company_name'];
            $sql = "SELECT * FROM company WHERE name = '$company_name'";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                $sql = "INSERT INTO company (name, address, zip, city, country) VALUES ('" . $row['company_name'] . "', '" . $row['company_address'] . "', '" .
                    $row['company_zip'] . "', '" . $row['company_city'] . "', '" . $row['company_country'] . "')";

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    exit();
                }
            }
        }

        //TESTING START
        // print out all keys from $data

        /*
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                echo $key . " => " . $value . "<br>";
            }
        }
        */

        //TESTING END

        foreach ($data as $row) {
            echo $row['number'] . " " . $row['first_name'] . " " . $row['last_name'] . " " . $row['company_name'] . " " . $row['stake'] . "<br>";
            $parcel_number = $row['number'];
            $sql = "SELECT SUM(stake) FROM ownership_list ol LEFT JOIN parcel p ON ol.id_parcel = p.id WHERE p.number = '" . $parcel_number . "' GROUP BY p.id";
            $result = $conn->query($sql);
            $sql_row = $result->fetch_assoc();
            $sumStake = $sql_row['SUM(stake)'] ?? 0;
            if ($sumStake > 1) {
                echo "Sorry, the stake of parcel number " . $parcel_number . " is more than 1.";
                exit();
            } else {
                $parcel_number = $row['number'];
                $sql = "SELECT id FROM parcel WHERE number = '$parcel_number'";
                $result = $conn->query($sql);
                $id_parcel = $result->fetch_assoc()['id'];
                $id_owner = null;
                $id_company = null;

                $first_name = $row['first_name'] ?? null;
                $last_name = $row['last_name'] ?? null;
                if ($first_name != null && $last_name != null) {
                    $sql = "SELECT id FROM owner WHERE first_name = '$first_name' AND last_name = '$last_name'";
                    $result = $conn->query($sql);
                    $id_owner = $result->fetch_assoc()['id'];
                }

                $company_name = $row['company_name'] ?? null;
                if ($company_name != null) {
                    $sql = "SELECT id FROM company WHERE name = '$company_name'";
                    $result = $conn->query($sql);
                    $id_company = $result->fetch_assoc()['id'];
                }

                if ($id_owner == null && $id_company == null) {
                    continue;
                } elseif ($id_owner == null) {
                    $sql = "INSERT INTO ownership_list (id_parcel, id_company, stake) VALUES ('" . $id_parcel . "', '" . $id_company . "', '" . $row['stake'] . "')";
                } elseif ($id_company == null) {
                    $sql = "INSERT INTO ownership_list (id_parcel, id_owner, stake) VALUES ('" . $id_parcel . "', '" . $id_owner . "', '" . $row['stake'] . "')";
                }
                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
}