<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("location:login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([":user_id" => $_SESSION["user_id"]]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $desc = $_POST["description"];
    $priority = $_POST["priority"];
    $status = $_POST["status"];
    if ($title && $desc && $priority && $status) {
        $stm = $conn->prepare("INSERT INTO tasks (user_id, title, description, priority, status) VALUES (:user_id, :title, :desc, :priority, :status)");
        $stm->execute([":user_id" => $_SESSION["user_id"], ":title" => $title, ":desc" => $desc, ":priority" => $priority, ":status" => $status]);
        header("Location: dashboard.php");
        exit();
    }
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <script src="sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="blob a" aria-hidden="true"></div>
    <div class="blob b" aria-hidden="true"></div>

    <div class="card">
        <div class="header-bar">
            <h1>Bienvenue <span><?= $_SESSION["user_name"] ?></span></h1>

            <div class="header-buttons">
                <button id="addT">Add Task</button>
                <button id="showT">Show Tasks</button>
                <a href="logout.php" class="logout-btn">Sign Out</a>
            </div>
        </div>

        <!-- ADD TASK FORM -->
        <div id="add_Task" hidden>
            <form action="#" method="post">
                <label>Title</label>
                <input type="text" name="title" required>
                <label>Description</label>
                <textarea name="description" required></textarea>
                <label>Priority</label>
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <input type="hidden" name="status" value="Pending">
                <input type="submit" value="Add Task">
            </form>
        </div>


        <!-- TASK TABLE -->
        <div id="show_Task">
            <div class="counters-row" id="countersRow">
                <div class="counter-card" role="region" aria-label="Completed tasks">
                    <div class="counter-title">Completed</div>
                    <div class="counter-circle completed" id="completed-circle">
                        <div class="counter-number" id="completed-count">0</div>
                    </div>
                    <div class="counter-sub">Tasks finished</div>
                </div>

                <div class="counter-card" role="region" aria-label="Pending tasks">
                    <div class="counter-title">Pending</div>
                    <div class="counter-circle pending" id="pending-circle">
                        <div class="counter-number" id="pending-count">0</div>
                    </div>
                    <div class="counter-sub">Waiting to be done</div>
                </div>

                <div class="counter-card" role="region" aria-label="Total tasks">
                    <div class="counter-title">Total</div>
                    <div class="counter-circle" id="total-circle">
                        <div class="counter-number" id="total-count">0</div>
                    </div>
                    <div class="counter-sub">All tasks</div>
                </div>
            </div>

            <div class="search-bar">
                <input type="text" id="task-search" placeholder="Search by title or description...">
            </div>
            <div class="filter-bar">
                <select id="filter-status">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>

                <select id="filter-priority">
                    <option value="all">All Priority</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php if ($tasks):
                    foreach ($tasks as $task): ?>
                        <tr data-id="<?= $task['id'] ?>">
                            <td class="title"><?= htmlspecialchars($task["title"]) ?></td>
                            <td class="description"><?= htmlspecialchars($task["description"]) ?></td>
                            <td id="priority"  class="priority"><?= htmlspecialchars($task["priority"]) ?></td>
                            <td id="status" class="<?= strtolower($task["status"]) ?>"><?= htmlspecialchars($task["status"]) ?></td>
                            <td>
                                <button type="button" class="edit"><img style="width:25px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAA70lEQVR4nO2UwQnCMBhG36F6dgF3EEHx4hCuo/QkuIFT1FZ7cBrBay+KI1QKXyGUQlNNCmI++A/5k+Y90hAICfGXCIiBuypWb5CMgAQoG7UbCn4S8AksgbXGt6Hhc/VXfQTKnpXo31bwtAU+17jqb10LJAY864DXa60FbGPCX8BC/RnwUD/VOlwLjICzS3gfgbEPuK2AN7iNQAXPOy5cJnjkQ2DTAT8b8MSHwF7zhxb4RSeE8RQ7F7hq/iiJGp4bcJt9PhYoWh6kJtybwFRzhU5irzvRhHsTmEji2338fNhIECiDAF8KlI7q9wRC/itvyqzGIKmr++YAAAAASUVORK5CYII=" alt="create-new"></button>
                                <button type="button" class="delete"><img style="width:25px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAvklEQVR4nO3VwQnCMBjF8Qe6hnYRO4FSPNcJ9FxBF9AJdAKdQCcwE9gJdAOPFaFKoFfBvPdJEPuH3JLvR3IJ0PZ5YwAZInQHUH1reNYMfwauin0NR2DvlosFn5ib/1c3g2f2M4IrDeAzAx8N4AMDbw3gDQMvDeAFA08M4JyBUwN4wMB9A7jHwB0ADwH1Z7sguwrwBUIu1uewF+CdAq8FeKXAMwGeKvBIgIcKnACoCbRuzkrNA/9mv7dQ0bbf7wXRGEWDOGx/GAAAAABJRU5ErkJggg==" alt="filled-trash"></button>
                            </td>
                        </tr>
                <?php endforeach;
                endif; ?>
            </table>
        </div>
    </div>

    <script src="assets/script.js"></script>

</body>

</html>