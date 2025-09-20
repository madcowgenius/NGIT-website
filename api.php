<?php
// Farm Tracker API - WAD621S Project
// Using stored procedures from Database Programming class
// This connects to SQL Server and calls my stored procedures

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database connection - using Windows Authentication
// Learned this from my previous project!
$serverName = "ISTODO\\SQLEXPRESS";  // Had to figure out the double backslash
$database = "farm_tracker";

try {
    // Windows Authentication - no username/password needed
    $dsn = "sqlsrv:Server=$serverName;Database=$database";
    $pdo = new PDO($dsn);  // This uses my Windows login automatically
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Start session for managing logged in users
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get the action from URL
$action = $_GET['action'] ?? '';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Route to the right function based on action
switch ($action) {
    case 'register':
        handleRegister($pdo, $input);
        break;
    case 'login':
        handleLogin($pdo, $input);
        break;
    case 'dashboard':
        handleDashboard($pdo, $input);
        break;
    case 'get_crops':
        handleGetCrops($pdo, $input);
        break;
    case 'add_crop':
        handleAddCrop($pdo, $input);
        break;
    case 'get_livestock':
        handleGetLivestock($pdo, $input);
        break;
    case 'add_livestock':
        handleAddLivestock($pdo, $input);
        break;
    case 'get_sales':
        handleGetSales($pdo, $input);
        break;
    case 'add_sale':
        handleAddSale($pdo, $input);
        break;
    default:
        echo json_encode(['error' => 'Unknown action']);
}

// ============================================
// AUTHENTICATION FUNCTIONS
// ============================================

function handleRegister($pdo, $input) {
    // Validate inputs
    if (empty($input['email']) || empty($input['password'])) {
        echo json_encode(['error' => 'Email and password required']);
        return;
    }
    
    // Hash the password - learned about this in security lecture
    $passwordHash = password_hash($input['password'], PASSWORD_DEFAULT);
    
    // Call stored procedure sp_RegisterUser
    // Had to use SET NOCOUNT ON to make it work with PDO
    $sql = "SET NOCOUNT ON; EXEC sp_RegisterUser 
            @first_name = ?, 
            @last_name = ?, 
            @email = ?, 
            @phone = ?, 
            @farm_name = ?, 
            @farm_location = ?, 
            @password_hash = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $input['first_name'],
            $input['last_name'],
            $input['email'],
            $input['phone'],
            $input['farm_name'],
            $input['farm_location'],
            $passwordHash
        ]);
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && isset($result['success']) && $result['success'] == 1) {
            echo json_encode(['message' => 'Registration successful', 'user_id' => $result['user_id']]);
        } elseif ($result && isset($result['message'])) {
            echo json_encode(['error' => $result['message']]);
        } else {
            echo json_encode(['message' => 'Registration successful']);
        }
    } catch(Exception $e) {
        echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);
    }
}

function handleLogin($pdo, $input) {
    // Get user by email using stored procedure
    $sql = "SET NOCOUNT ON; EXEC sp_LoginUser @email = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$input['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if user exists and password is correct
        if ($user && password_verify($input['password'], $user['password_hash'])) {
            // Remove password hash before sending to client
            unset($user['password_hash']);
            echo json_encode(['message' => 'Login successful', 'user' => $user]);
        } else {
            echo json_encode(['error' => 'Invalid email or password']);
        }
    } catch(Exception $e) {
        echo json_encode(['error' => 'Login failed: ' . $e->getMessage()]);
    }
}

// ============================================
// DASHBOARD FUNCTION
// ============================================

function handleDashboard($pdo, $input) {
    // Get dashboard statistics using stored procedure
    $sql = "SET NOCOUNT ON; EXEC sp_GetDashboardStats @user_id = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$input['user_id']]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($stats);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Failed to get stats: ' . $e->getMessage()]);
    }
}

// ============================================
// CROP FUNCTIONS
// ============================================

function handleGetCrops($pdo, $input) {
    // Get all crops for user
    $sql = "SET NOCOUNT ON; EXEC sp_GetCrops @user_id = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$input['user_id']]);
        $crops = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($crops);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Failed to get crops: ' . $e->getMessage()]);
    }
}

function handleAddCrop($pdo, $input) {
    // Add new crop using stored procedure
    $sql = "SET NOCOUNT ON; EXEC sp_AddCrop 
            @user_id = ?,
            @crop_name = ?,
            @crop_type = ?,
            @planting_date = ?,
            @expected_harvest_date = ?,
            @planted_area = ?,
            @planting_cost = ?,
            @status = ?,
            @notes = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $input['user_id'],
            $input['crop_name'],
            $input['crop_type'],
            $input['planting_date'],
            $input['expected_harvest_date'] ?? null,
            $input['planted_area'],
            $input['planting_cost'],
            $input['status'] ?? 'planted',
            $input['notes'] ?? ''
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Failed to add crop: ' . $e->getMessage()]);
    }
}

// ============================================
// LIVESTOCK FUNCTIONS
// ============================================

function handleGetLivestock($pdo, $input) {
    $sql = "SET NOCOUNT ON; EXEC sp_GetLivestock @user_id = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$input['user_id']]);
        $livestock = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($livestock);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Failed to get livestock: ' . $e->getMessage()]);
    }
}

function handleAddLivestock($pdo, $input) {
    $sql = "SET NOCOUNT ON; EXEC sp_AddLivestock
            @user_id = ?,
            @animal_type = ?,
            @breed = ?,
            @quantity = ?,
            @purchase_date = ?,
            @purchase_price = ?,
            @age_months = ?,
            @weight = ?,
            @health_status = ?,
            @location = ?,
            @notes = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $input['user_id'],
            $input['animal_type'],
            $input['breed'] ?? '',
            $input['quantity'],
            $input['purchase_date'],
            $input['purchase_price'],
            $input['age_months'],
            $input['weight'],
            $input['health_status'] ?? 'healthy',
            $input['location'] ?? '',
            $input['notes'] ?? ''
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Failed to add livestock: ' . $e->getMessage()]);
    }
}

// ============================================
// SALES FUNCTIONS
// ============================================

function handleGetSales($pdo, $input) {
    $sql = "SET NOCOUNT ON; EXEC sp_GetSales @user_id = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$input['user_id']]);
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($sales);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Failed to get sales: ' . $e->getMessage()]);
    }
}

function handleAddSale($pdo, $input) {
    $sql = "SET NOCOUNT ON; EXEC sp_AddSale
            @user_id = ?,
            @sale_type = ?,
            @item_id = ?,
            @buyer_name = ?,
            @buyer_contact = ?,
            @sale_date = ?,
            @quantity = ?,
            @unit_price = ?,
            @payment_method = ?,
            @payment_status = ?,
            @notes = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $input['user_id'],
            $input['sale_type'],
            $input['item_id'],
            $input['buyer_name'],
            $input['buyer_contact'] ?? '',
            $input['sale_date'],
            $input['quantity'],
            $input['unit_price'],
            $input['payment_method'] ?? 'cash',
            $input['payment_status'] ?? 'pending',
            $input['notes'] ?? ''
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch(Exception $e) {
        echo json_encode(['error' => 'Failed to add sale: ' . $e->getMessage()]);
    }
}

?>
