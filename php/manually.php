<?php
session_start();
include "../templates/manually.html";
include "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['formName'] == 'dataInsertPicker') {
        $tableName = $_POST['table'] ?? '';
        switch ($tableName) {
            case 'company':
                include "../templates/write/company.html";
                break;
            case 'owner':
                include "../templates/write/owner.html";
                break;
            case 'ownership_list':
                $parcels = $conn->query("SELECT id, number FROM parcel");
                $owners = $conn->query("SELECT id, first_name, last_name FROM owner");
                $companies = $conn->query("SELECT id, name FROM company");

                if ($parcels->num_rows > 0) {
                    $parcels = $parcels->fetch_all(MYSQLI_ASSOC);
                } else {
                    $parcels = [];
                }
                if ($owners->num_rows > 0) {
                    $owners = $owners->fetch_all(MYSQLI_ASSOC);
                } else {
                    $owners = [];
                }
                if ($companies->num_rows > 0) {
                    $companies = $companies->fetch_all(MYSQLI_ASSOC);
                } else {
                    $companies = [];
                }

                $parcels = array_filter($parcels, function ($parcel) use ($conn) {
                    $result = $conn->query("SELECT SUM(stake) FROM ownership_list WHERE id_parcel = " . $parcel['id']);
                    $sum = $result->fetch_row()[0];
                    return $sum < 1;
                });

                if (empty($parcels)) {
                    displayMessage("Error: All parcels are fully owned", "danger");
                    break;
                } else {
                    include "../templates/write/ownership_list.html";
                    break;
                }
            case 'parcel':
                include "../templates/write/parcel.html";
                break;
            case 'user':
                include "../templates/write/user.html";
                break;
        }
    } else {
        $stmt = null;
        $formName = $_POST['formName'] ?? '';
        switch ($formName) {
            case 'companyInsertManually':
                $stmt = handleCompanyInsertManually($conn);
                break;
            case 'ownerInsertManually':
                $stmt = handleOwnerInsertManually($conn);
                break;
            case 'userInsertManually':
                $stmt = handleUserInsertManually($conn);
                break;
            case 'ownershipListInsertManually':
                $stmt = handleOwnershipListInsertManually($conn);
                break;
            case 'parcelInsertManually':
                $stmt = handleParcelInsertManually($conn);
            default:
                break;
        }
    }
    if (isset($stmt)) {
        $conn->begin_transaction();
        if (!$stmt->execute()) {
            displayMessage("Error: " . $conn->error, "danger");
            $conn->rollback();
        } else {
            displayMessage("Data has been uploaded successfully", "success");
            $conn->commit();
        }
        $stmt->close();
        $conn->close();
    }
}
function handleUserInsertManually(mysqli $conn): mysqli_stmt|null
{
    if (!filter_var($_POST['formEmail'], FILTER_VALIDATE_EMAIL)) {
        displayMessage("Error: Invalid email", "danger");
        return null;
    } else {
        $email = filter_input(INPUT_POST, 'formEmail', FILTER_SANITIZE_EMAIL);
    }
    $password = filter_input(INPUT_POST, 'formPassword', FILTER_SANITIZE_STRING);
    $password = password_hash($conn->real_escape_string($password), PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (email, password, isCool) VALUES (?, ?, 0)");
    $stmt->bind_param("ss", $email, $password);

    $sql_user = "SELECT id FROM user WHERE email = '" . $_SESSION['email'] . "'";
    $id_user = $conn->query($sql_user)->fetch_assoc()['id'];
    $sql_log = "INSERT INTO log (type, id_user, description) VALUES ('insert', '" . $id_user . "', 'Insert data into table user')";
    $conn->query($sql_log);

    return $stmt;
}

function handleCompanyInsertManually(mysqli $conn): mysqli_stmt|null
{
    $companyName = filter_input(INPUT_POST, 'formName', FILTER_SANITIZE_STRING);
    $companyAddress = filter_input(INPUT_POST, 'formAddress', FILTER_SANITIZE_STRING);
    $companyZip = filter_input(INPUT_POST, 'formZip', FILTER_SANITIZE_STRING);
    $companyCity = filter_input(INPUT_POST, 'formCity', FILTER_SANITIZE_STRING);
    $companyCountry = strtoupper(filter_input(INPUT_POST, 'formCountry', FILTER_SANITIZE_STRING));

    $companyZip = preg_replace('/\s+/', '', $companyZip);
    if (!is_numeric($companyZip)) {
        displayMessage("Error: Zip must be a number", "danger");
        return null;
    } else {
        $companyZip = (int)$companyZip;
    }

    $sql = "INSERT INTO company (name, address, zip, city, country) VALUES (?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $companyName, $companyAddress, $companyZip, $companyCity, $companyCountry);

    $sql_user = "SELECT id FROM user WHERE email = '" . $_SESSION['email'] . "'";
    $id_user = $conn->query($sql_user)->fetch_assoc()['id'];
    $sql_log = "INSERT INTO log (type, id_user, description) VALUES ('insert', '" . $id_user . "', 'Insert data into table company')";
    $conn->query($sql_log);

    return $stmt;
}

function handleOwnerInsertManually(mysqli $conn): mysqli_stmt|null
{
    $firstName = $conn->real_escape_string($_POST['formFirstName']);
    $lastName = $conn->real_escape_string($_POST['formLastName']);
    $phone = $conn->real_escape_string($_POST['formPhone']);

    $stmt = $conn->prepare("INSERT INTO owner (first_name, last_name, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $firstName, $lastName, $phone);

    $sql_user = "SELECT id FROM user WHERE email = '" . $_SESSION['email'] . "'";
    $id_user = $conn->query($sql_user)->fetch_assoc()['id'];
    $sql_log = "INSERT INTO log (type, id_user, description) VALUES ('insert', '" . $id_user . "', 'Insert data into table user')";
    $conn->query($sql_log);

    return $stmt;
}

function handleOwnershipListInsertManually(mysqli $conn): mysqli_stmt|null
{
    $allCurrentStakes = $conn->query("SELECT SUM(stake) FROM ownership_list WHERE id_parcel = " . $conn->real_escape_string($_POST['formParcel']))->fetch_row()[0];
    if ($allCurrentStakes + (float)$_POST['formStake'] > 1) {
        displayMessage("Error: Stake is too high", "danger");
        return null;
    }

    $parcelId = $conn->real_escape_string($_POST['formParcel']);
    if (!is_numeric($_POST['formStake'])) {
        displayMessage("Error: Stake must be a number", "danger");
        return null;
    } else {
        $stake = (float)$_POST['formStake'];
    }
    if (str_starts_with($_POST['formOwnerCompany'], 'c')) {
        $companyId = (int)substr($_POST['formOwnerCompany'], 1);
        $stmt = $conn->prepare("INSERT INTO ownership_list (id_parcel, id_company, stake) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $parcelId, $companyId, $stake);
    } elseif (str_starts_with($_POST['formOwnerCompany'], 'o')) {
        $ownerId = (int)substr($_POST['formOwnerCompany'], 1);
        $stmt = $conn->prepare("INSERT INTO ownership_list (id_parcel, id_owner, stake) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $parcelId, $ownerId, $stake);
    } else {
        displayMessage("Error: Invalid id", "danger");
        echo "ParcelID: " . $parcelId . "| Stake: " . $stake . "| Owner/Company: " . $_POST['formOwnerCompany'];
        return null;
    }
    $sql_user = "SELECT id FROM user WHERE email = '" . $_SESSION['email'] . "'";
    $id_user = $conn->query($sql_user)->fetch_assoc()['id'];
    $sql_log = "INSERT INTO log (type, id_user, description) VALUES ('insert', '" . $id_user . "', 'Insert data into table ownership_list')";
    $conn->query($sql_log);

    return $stmt;
}

function handleParcelInsertManually(mysqli $conn): myslqli_stmt|null
{
    $number = $conn->real_escape_string($_POST['formNumber']);
    $size = $conn->real_escape_string($_POST['formSize']);
    $latitude = $conn->real_escape_string($_POST['formLatitude']);
    $longitude = $conn->real_escape_string($_POST['formLongitude']);
    $legalState = $conn->real_escape_string($_POST['formLegalState']);
    $type = $conn->real_escape_string($_POST['formType']);
    $address = $conn->real_escape_string($_POST['formAddress']);
    $zip = $conn->real_escape_string($_POST['formZip']);
    $city = $conn->real_escape_string($_POST['formCity']);
    $country = strtoupper($conn->real_escape_string($_POST['formCountry']));
    $zip = preg_replace('/\s+/', '', $zip);
    if (!is_numeric($zip)) {
        displayMessage("Error: Zip must be a number", "danger");
        return null;
    } else {
        $zip = (int)$zip;
    }

    $legalState = match ($legalState) {
        0 => "owned",
        1 => "leased",
        2 => "pledged",
        default => function () {
            displayMessage("Error: Invalid legal state", "danger");
            return null;
        }
    };
    $type = match ($type) {
        0 => 'zastavěné plochy a nádvoří',
        1 => 'zemědělské pozemky',
        2 => 'lesní pozemky',
        3 => 'vodní plochy',
        4 => 'ostatní plochy',
        default => function () {
            displayMessage("Error: Invalid type", "danger");
            return null;
        }
    };


    $sql = "INSERT INTO parcel (number, size, latitude, longitude, legal_state, type, address, zip, city, country, date_of_ownership) VALUES (?,?,?,?,?,?,?,?,?,?," . GETDATE() . ")";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idddsssisss", $number, $size, $latitude, $longitude, $legalState, $type, $address, $zip, $city, $country);

    $sql_user = "SELECT id FROM user WHERE email = '" . $_SESSION['email'] . "'";
    $id_user = $conn->query($sql_user)->fetch_assoc()['id'];
    $sql_log = "INSERT INTO log (type, id_user, description) VALUES ('insert', '" . $id_user . "', 'Insert data into table parcel')";
    $conn->query($sql_log);

    return $stmt;
}

function displayMessage(string $message, string $type): void
{
    echo "<div class='alert alert-" . $type . "' role='alert' id='errorAlert'>" . $message . "</div>";
    echo "<script>setTimeout(function() { document.getElementById('errorAlert').style.display='none'; }, 3000);</script>";
}