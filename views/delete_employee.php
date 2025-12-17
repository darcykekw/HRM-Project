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
if ($employee->delete($id)) {
    header("Location: list_employees.php");
} else {
    echo "Unable to delete employee.";
}
?>