<?php
    include('adminheader.php');
    include('adminnav.php');
    include('../db_connect.php'); 

  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $event = $conn->real_escape_string($_POST['event']);
        $title = $conn->real_escape_string($_POST['title']);
        $start_date = $conn->real_escape_string($_POST['start_date']);
        $start_time = $conn->real_escape_string($_POST['start_time']);
        $description = $conn->real_escape_string($_POST['description']);

        //optional end date and end time
        $end_date = !empty($_POST['end_date']) ? "'".$conn->real_escape_string($_POST['end_date'])."'" : "NULL";
        $end_time = !empty($_POST['end_time']) ? "'".$conn->real_escape_string($_POST['end_time'])."'" : "NULL";

        if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
            // Update an existing post
            $post_id = $conn->real_escape_string($_POST['post_id']);
            $sql = "UPDATE community SET event='$event', title='$title', start_date='$start_date', end_date=$end_date, start_time='$start_time', end_time=$end_time, description='$description' WHERE id='$post_id'";
            if ($conn->query($sql) === TRUE) {
                header("Location: admincommunity.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // Add a new post
            $sql = "INSERT INTO community (event, title, start_date, end_date, start_time, end_time, description) 
                    VALUES ('$event', '$title', '$start_date', $end_date, '$start_time', $end_time, '$description')";
            if ($conn->query($sql) === TRUE) {
                header("Location: admincommunity.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // delete request
    if (isset($_GET['delete'])) {
        $id = $conn->real_escape_string($_GET['delete']);
        $sql = "DELETE FROM community WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            header("Location: admincommunity.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $result = $conn->query("SELECT * FROM community");
?>

<link rel="stylesheet" href="../index.css">

<main>
    <h2>Manage Community & Partnerships</h2>

    <section class="contact-form">
        <h3><?php echo isset($_GET['edit']) ? 'Edit' : 'Add'; ?> Community Post</h3>
        <form action="<?php echo basename($_SERVER['REQUEST_URI']); ?>" method="post">
            <?php
                if (isset($_GET['edit'])) {
                    $id = $conn->real_escape_string($_GET['edit']);
                    $edit_result = $conn->query("SELECT * FROM community WHERE id = $id");
                    $edit_row = $edit_result->fetch_assoc();
            ?>
                <input type="hidden" name="post_id" value="<?php echo $edit_row['id']; ?>">
                <label>Event</label>
                <input type="text" name="event" value="<?php echo $edit_row['event']; ?>" required>
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $edit_row['title']; ?>" required>
                <label>Start Date</label>
                <input type="date" name="start_date" value="<?php echo $edit_row['start_date']; ?>" required>
                <label>End Date</label>
                <input type="date" name="end_date" value="<?php echo $edit_row['end_date']; ?>">
                <label>Start Time</label>
                <input type="time" name="start_time" value="<?php echo $edit_row['start_time']; ?>" required>
                <label>End Time</label>
                <input type="time" name="end_time" value="<?php echo $edit_row['end_time']; ?>">
                <label>Description</label>
                <textarea id="description" name="description" placeholder="Enter your Message" style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; line-height: 1.5; box-sizing: border-box; resize: vertical; outline: none;">
<?php echo $edit_row['description']; ?>
</textarea>
                <button type="submit" class="btn btn-primary">Update Post</button>
            <?php
                } else {
            ?>
                <label>Event</label>
                <input type="text" name="event" required>
                <label>Title</label>
                <input type="text" name="title" required>
                <label>Start Date</label>
                <input type="date" name="start_date" required>
                <label>End Date</label>
                <input type="date" name="end_date">
                <label>Start Time</label>
                <input type="time" name="start_time" required>
                <label>End Time</label>
                <input type="time" name="end_time">
                <label>Description</label>
                <textarea id="description" name="description" placeholder="Enter your Message" style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; line-height: 1.5; box-sizing: border-box; resize: vertical; outline: none;">

</textarea>                <button type="submit" class="btn btn-primary">Add Post</button>
            <?php } ?>
        </form>
    </section>

    <section class="book-list">
        <h3>Community Posts</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['event']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['start_date']; ?> - <?php echo $row['end_date']; ?></td>
                        <td><?php echo $row['start_time']; ?> - <?php echo $row['end_time']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <a href="admincommunity.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a style="color:red;" href="admincommunity.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php
    include('../footer.php');
    $conn->close();
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const startDateInput = document.querySelector("input[name='start_date']");
    const endDateInput = document.querySelector("input[name='end_date']");
    const startTimeInput = document.querySelector("input[name='start_time']");
    const endTimeInput = document.querySelector("input[name='end_time']");
    
    form.addEventListener("submit", function(event) {
        const today = new Date().toISOString().split('T')[0];
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        // Validate start date is not before today
        if (startDate < today) {
            alert("Start date cannot be before today.");
            event.preventDefault();
            return;
        }

        // Validate end date is after start date
        if (endDate && endDate <= startDate) {
            alert("End date must be after the start date.");
            event.preventDefault();
            return;
        }

        // Validate start time and end time on the same day
        if (startDate === endDate && endTime <= startTime) {
            alert("End time must be after start time on the same day.");
            event.preventDefault();
            return;
        }
    });

    // Automatically set minimum end date based on start date
    startDateInput.addEventListener("change", function() {
        const minEndDate = new Date(startDateInput.value);
        minEndDate.setDate(minEndDate.getDate() + 1);
        endDateInput.min = minEndDate.toISOString().split('T')[0];
    });
});
</script>
