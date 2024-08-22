<?php
include 'db.php';
$id = $_GET['id'];

// Fetch the current employee's data
$result = $conn->query("SELECT * FROM employees WHERE id=$id");
$row = $result->fetch_assoc();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $aadhaar = trim($_POST['aadhaar']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    // Validation for duplicate mobile number
    $mobileCheck = "SELECT id FROM employees WHERE mobile='$mobile' AND id != $id";
    $mobileResult = $conn->query($mobileCheck);
    if ($mobileResult->num_rows > 0) {
        $errors['mobile'] = "This mobile number is already registered for another employee.";
    }

    // Validation for duplicate Aadhaar number
    $aadhaarCheck = "SELECT id FROM employees WHERE aadhaar='$aadhaar' AND id != $id";
    $aadhaarResult = $conn->query($aadhaarCheck);
    if ($aadhaarResult->num_rows > 0) {
        $errors['aadhaar'] = "This Aadhaar number is already registered for another employee.";
    }

    // Validation for duplicate email
    $emailCheck = "SELECT id FROM employees WHERE email='$email' AND id != $id";
    $emailResult = $conn->query($emailCheck);
    if ($emailResult->num_rows > 0) {
        $errors['email'] = "This email is already registered for another employee.";
    }

    // If there are no validation errors, proceed to update the record
    if (empty($errors)) {
        $sql = "UPDATE employees SET 
                name='$name', mobile='$mobile', aadhaar='$aadhaar', email='$email', dob='$dob', gender='$gender' 
                WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Edit Employee</h2>
    <!-- Button to go back to index page -->
    <div class="mb-3 text-right">
        <a href="index.php" class="btn btn-secondary">Back to Employee List</a>
    </div>
    
    <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) {
                echo $error . "<br>";
            } ?>
        </div>
    <?php } ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Staff Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="mobile">Staff Mobile:</label>
            <input type="text" class="form-control <?php echo isset($errors['mobile']) ? 'is-invalid' : ''; ?>" id="mobile" name="mobile" value="<?php echo $row['mobile']; ?>" required pattern="[0-9]{10}">
            <?php if (isset($errors['mobile'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['mobile']; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="aadhaar">Staff Aadhaar No:</label>
            <input type="text" class="form-control <?php echo isset($errors['aadhaar']) ? 'is-invalid' : ''; ?>" id="aadhaar" name="aadhaar" value="<?php echo $row['aadhaar']; ?>" required pattern="[0-9]{12}">
            <?php if (isset($errors['aadhaar'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['aadhaar']; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="email">Staff Email:</label>
            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $row['email']; ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['email']; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $row['dob']; ?>" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="male" <?php if ($row['gender'] == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if ($row['gender'] == 'female') echo 'selected'; ?>>Female</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>