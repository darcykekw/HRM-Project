<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/Employee.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireLogin();

$role = $auth->getUserRole();
$employee = new Employee($db);

$search = isset($_GET['search']) ? $_GET['search'] : "";
$stmt = $employee->readAll($search);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee List - HRMIS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../dashboard.php">HRMIS</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../dashboard.php">Dashboard</a>
                </li>
                <?php if ($role == 'HR Staff' || $role == 'HR Head'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="add_employee.php">Add Employee</a>
                </li>
                <?php endif; ?>
                <li class="nav-item active">
                    <a class="nav-link" href="list_employees.php">Employees</a>
                </li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Employee List</h2>
        
        <form class="form-inline mb-3" method="GET">
            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search by Name or ID" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['surname'] . ', ' . $row['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td>
                        <a href="view_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                        <?php if ($role == 'HR Staff' || $role == 'HR Head'): ?>
                        <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php endif; ?>
                        <?php if ($role == 'HR Staff' || $role == 'HR Head'): ?>
                        <a href="delete_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>