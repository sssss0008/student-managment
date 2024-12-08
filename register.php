<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $age = intval($_POST['age']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $class = intval($_POST['class']);
    $subjects = $_POST['subjects']; // Array of subjects

    // Validation
    if (empty($name) || empty($age) || empty($email) || empty($phone) || empty($class) || empty($subjects)) {
        $error = "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $error = "Invalid phone number. Must be 10 digits.";
    } else {
        // Insert into students table
        $stmt = $conn->prepare("INSERT INTO students (name, age, email, phone, class) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $name, $age, $email, $phone, $class);

        if ($stmt->execute()) {
            $student_id = $stmt->insert_id;

            // Insert subjects
            $subject_stmt = $conn->prepare("INSERT INTO subjects (student_id, subject_name) VALUES (?, ?)");
            foreach ($subjects as $subject) {
                $subject_stmt->bind_param("is", $student_id, $subject);
                $subject_stmt->execute();
            }

            $success = "Student registered successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
</head>
<body>
    <h1>Register a Student</h1>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST" action="">
        <label>Name: </label>
        <input type="text" name="name" required><br>

        <label>Age: </label>
        <input type="number" name="age" min="5" max="18" required><br>

        <label>Email: </label>
        <input type="email" name="email" required><br>

        <label>Phone: </label>
        <input type="text" name="phone" maxlength="10" required><br>

        <label>Class: </label>
        <select name="class" required>
            <?php for ($i = 1; $i <= 10; $i++) {
                echo "<option value='$i'>Class $i</option>";
            } ?>
        </select><br>

        <label>Subjects: </label>
        <select name="subjects[]" multiple required>
            <option value="Math">Math</option>
            <option value="Science">Science</option>
            <option value="English">English</option>
            <option value="History">History</option>
            <option value="Geography">Geography</option>
        </select><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
