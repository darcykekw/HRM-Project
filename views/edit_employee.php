<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/Employee.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireRole(['HR Staff', 'HR Head']);

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
$employee = new Employee($db);
$row = $employee->readOne($id);

if (!$row) {
    die('ERROR: Employee not found.');
}

$message = '';
$error = '';

if ($_POST) {
    // Basic validation
    if (empty($_POST['surname']) || empty($_POST['firstname']) || empty($_POST['date_of_birth'])) {
        $error = "Please fill in all required fields.";
    } else {
        $data = [
            'id' => $id,
            'surname' => $_POST['surname'],
            'firstname' => $_POST['firstname'],
            'middlename' => $_POST['middlename'] ?? '',
            'name_extension' => $_POST['name_extension'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'],
            'place_of_birth' => $_POST['place_of_birth'],
            'sex' => $_POST['sex'],
            'civil_status' => $_POST['civil_status'],
            'height_in_meter' => $_POST['height_in_meter'] ?? 0,
            'weight_in_kg' => $_POST['weight_in_kg'] ?? 0,
            'blood_type' => $_POST['blood_type'] ?? '',
            'gsis_id' => $_POST['gsis_id'] ?? '',
            'pagibig_id' => $_POST['pagibig_id'] ?? '',
            'philhealth_no' => $_POST['philhealth_no'] ?? '',
            'sss_no' => $_POST['sss_no'] ?? '',
            'tin_no' => $_POST['tin_no'] ?? '',
            'citizenship' => $_POST['citizenship'] ?? '',
            'residential_address' => $_POST['residential_address'] ?? '',
            'residential_zip' => $_POST['residential_zip'] ?? '',
            'permanent_address' => $_POST['permanent_address'] ?? '',
            'permanent_zip' => $_POST['permanent_zip'] ?? '',
            'telephone_no' => $_POST['telephone_no'] ?? '',
            'mobile_no' => $_POST['mobile_no'] ?? '',
            'email_address' => $_POST['email_address'] ?? '',
            'department' => $_POST['department'] ?? '',
            'position' => $_POST['position'] ?? '',
            'date_hired' => $_POST['date_hired'] ?? ''
        ];

        if ($employee->update($id, $data)) {
            $message = "Employee record updated successfully!";
            // Refresh data
            $row = $employee->readOne($id);
        } else {
            $error = "Unable to update employee.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee - HRMIS</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="list_employees.php">Employees</a>
                </li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h2>Edit Employee (CSC Form 212)</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <h4 class="mt-4">I. Personal Information</h4>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Employee ID</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['employee_no']); ?>" disabled>
                    <small class="form-text text-muted">Cannot be modified.</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Surname *</label>
                    <input type="text" name="surname" class="form-control" value="<?php echo htmlspecialchars($row['surname']); ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>First Name *</label>
                    <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Middle Name</label>
                    <input type="text" name="middlename" class="form-control" value="<?php echo htmlspecialchars($row['middlename']); ?>">
                </div>
                <div class="form-group col-md-1">
                    <label>Ext.</label>
                    <input type="text" name="name_extension" class="form-control" value="<?php echo htmlspecialchars($row['name_extension']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Date of Birth *</label>
                    <input type="date" name="date_of_birth" class="form-control" value="<?php echo htmlspecialchars($row['date_of_birth']); ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Place of Birth *</label>
                    <input type="text" name="place_of_birth" class="form-control" value="<?php echo htmlspecialchars($row['place_of_birth']); ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Sex *</label>
                    <select name="sex" class="form-control" required>
                        <option value="Male" <?php echo ($row['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($row['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Civil Status *</label>
                    <select name="civil_status" class="form-control" required>
                        <option value="Single" <?php echo ($row['civil_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?php echo ($row['civil_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                        <option value="Widowed" <?php echo ($row['civil_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                        <option value="Separated" <?php echo ($row['civil_status'] == 'Separated') ? 'selected' : ''; ?>>Separated</option>
                        <option value="Other" <?php echo ($row['civil_status'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-2">
                    <label>Height (m)</label>
                    <input type="number" step="0.01" name="height_in_meter" class="form-control" value="<?php echo htmlspecialchars($row['height_in_meter']); ?>">
                </div>
                <div class="form-group col-md-2">
                    <label>Weight (kg)</label>
                    <input type="number" step="0.01" name="weight_in_kg" class="form-control" value="<?php echo htmlspecialchars($row['weight_in_kg']); ?>">
                </div>
                <div class="form-group col-md-2">
                    <label>Blood Type</label>
                    <input type="text" name="blood_type" class="form-control" value="<?php echo htmlspecialchars($row['blood_type']); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Citizenship</label>
                    <input type="text" name="citizenship" class="form-control" value="<?php echo htmlspecialchars($row['citizenship']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>GSIS ID No.</label>
                    <input type="text" name="gsis_id" class="form-control" value="<?php echo htmlspecialchars($row['gsis_id']); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>PAG-IBIG ID No.</label>
                    <input type="text" name="pagibig_id" class="form-control" value="<?php echo htmlspecialchars($row['pagibig_id']); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>PhilHealth No.</label>
                    <input type="text" name="philhealth_no" class="form-control" value="<?php echo htmlspecialchars($row['philhealth_no']); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>SSS No.</label>
                    <input type="text" name="sss_no" class="form-control" value="<?php echo htmlspecialchars($row['sss_no']); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>TIN No.</label>
                    <input type="text" name="tin_no" class="form-control" value="<?php echo htmlspecialchars($row['tin_no']); ?>">
                </div>
            </div>

            <h5 class="mt-3">Contact Information</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Residential Address</label>
                    <textarea name="residential_address" class="form-control" rows="2"><?php echo htmlspecialchars($row['residential_address']); ?></textarea>
                </div>
                <div class="form-group col-md-2">
                    <label>Zip Code</label>
                    <input type="text" name="residential_zip" class="form-control" value="<?php echo htmlspecialchars($row['residential_zip']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Permanent Address</label>
                    <textarea name="permanent_address" class="form-control" rows="2"><?php echo htmlspecialchars($row['permanent_address']); ?></textarea>
                </div>
                <div class="form-group col-md-2">
                    <label>Zip Code</label>
                    <input type="text" name="permanent_zip" class="form-control" value="<?php echo htmlspecialchars($row['permanent_zip']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Telephone No.</label>
                    <input type="text" name="telephone_no" class="form-control" value="<?php echo htmlspecialchars($row['telephone_no']); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Mobile No.</label>
                    <input type="text" name="mobile_no" class="form-control" value="<?php echo htmlspecialchars($row['mobile_no']); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Email Address</label>
                    <input type="email" name="email_address" class="form-control" value="<?php echo htmlspecialchars($row['email_address']); ?>">
                </div>
            </div>

            <h5 class="mt-3">Employment Details</h5>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Department</label>
                    <input type="text" name="department" class="form-control" value="<?php echo htmlspecialchars($row['department']); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Position</label>
                    <input type="text" name="position" class="form-control" value="<?php echo htmlspecialchars($row['position']); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Date Hired</label>
                    <input type="date" name="date_hired" class="form-control" value="<?php echo htmlspecialchars($row['date_hired']); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Record</button>
            <a href="list_employees.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</body>
</html>