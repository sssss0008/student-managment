<?php
include 'db.php';

$result = $conn->query("SELECT * FROM students");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Student Records</h1>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Age</th><th>Email</th><th>Phone</th><th>Class</th><th>Subjects</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $student_id = $row['id'];

                // Fetch subjects
                $subjects_result = $conn->query("SELECT subject_name FROM subjects WHERE student_id = $student_id");
                $subjects = [];
                while ($subject_row = $subjects_result->fetch_assoc()) {
                    $subjects[] = $subject_row['subject_name'];
                }

                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['class']}</td>
                    <td>" . implode(", ", $subjects) . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No records found.</p>";
        }
        ?>
    </div>
</body>
</html>
