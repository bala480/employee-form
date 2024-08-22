<?php
include 'db.php';
$errors = [];
$name = $mobile = $aadhaar = $email = $dob = $gender = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $aadhaar = trim($_POST['aadhaar']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
     // Validation
    if (empty($name)) {
        $errors['name'] = "Staff Name is required.";
    }
    if (empty($mobile) || !preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors['mobile'] = "Enter a valid 10-digit Mobile Number.";
    }else {
        // Check if the mobile number already exists in the database
        $mobileCheck = "SELECT id FROM employees WHERE mobile = '$mobile'";
        $result = $conn->query($mobileCheck);

        if ($result->num_rows > 0) {
            $errors['mobile'] = "This mobile number is already registered. Please use a different mobile number.";
        }
    }
    if (empty($aadhaar) || !preg_match("/^[0-9]{12}$/", $aadhaar)) {
        $errors['aadhaar'] = "Enter a valid 12-digit Aadhaar Number.";
    }else {
        // Check if the Aadhaar number already exists in the database
        $aadhaarCheck = "SELECT id FROM employees WHERE aadhaar = '$aadhaar'";
        $result = $conn->query($aadhaarCheck);

        if ($result->num_rows > 0) {
            $errors['aadhaar'] = "This Aadhaar number is already registered. Please use a different Aadhaar number.";
        }
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid Email Address.";
    } else {
        // Check if email already exists in the database
        $emailCheck = "SELECT id FROM employees WHERE email = '$email'";
        $result = $conn->query($emailCheck);

        if ($result->num_rows > 0) {
            $errors['email'] = "This email is already registered. Please use a different email address.";
        }
    }
    if (empty($dob)) {
        $errors['dob'] = "Date of Birth is required.";
    }
    if (empty($gender)) {
        $errors['gender'] = "Gender is required.";
    }

    // If no errors, insert into the database
    if (empty($errors)) {
        $sql = "INSERT INTO employees (name, mobile, aadhaar, email, dob, gender) 
                VALUES ('$name', '$mobile', '$aadhaar', '$email', '$dob', '$gender')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Form submitted successfully!</div>";
            header("location:index.php");
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   
<div class="container mt-5">
    <h2 class="text-center">Add New Employee</h2>
<!-- Button to go back to index page -->
    <div class="mb-3 text-right">
        <a href="index.php" class="btn btn-secondary">Back to Employee List</a>
    </div>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Staff Name</label>
            <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['name']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="text" class="form-control <?php echo isset($errors['mobile']) ? 'is-invalid' : ''; ?>" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>">
            <?php if (isset($errors['mobile'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['mobile']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="aadhaar">Aadhaar No</label>
            <input type="text" class="form-control <?php echo isset($errors['aadhaar']) ? 'is-invalid' : ''; ?>" id="aadhaar" name="aadhaar" value="<?php echo htmlspecialchars($aadhaar); ?>">
            <?php if (isset($errors['aadhaar'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['aadhaar']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['email']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control <?php echo isset($errors['dob']) ? 'is-invalid' : ''; ?>" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
            <?php if (isset($errors['dob'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['dob']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control <?php echo isset($errors['gender']) ? 'is-invalid' : ''; ?>" id="gender" name="gender">
                <option value="">Select Gender</option>
                <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
            </select>
            <?php if (isset($errors['gender'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['gender']; ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>