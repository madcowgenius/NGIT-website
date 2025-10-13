// Farm Tracker JavaScript
// My first time building a web app with API calls!
// Learned JavaScript from W3Schools and YouTube

// ============================================
// HELPER FUNCTIONS
// ============================================

// Simple function to make API calls
function callAPI(action, data) {
    return fetch('api.php?action=' + action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .catch(error => {
        console.error('API Error:', error);
        alert('Something went wrong: ' + error);
    });
}

// Get form data from a form
function getFormData(formId) {
    var form = document.getElementById(formId);
    var data = {};
    var inputs = form.getElementsByTagName('input');
    
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].name) {
            data[inputs[i].name] = inputs[i].value;
        }
    }
    
    // Also get select fields
    var selects = form.getElementsByTagName('select');
    for (var i = 0; i < selects.length; i++) {
        if (selects[i].name) {
            data[selects[i].name] = selects[i].value;
        }
    }
    
    // And textareas
    var textareas = form.getElementsByTagName('textarea');
    for (var i = 0; i < textareas.length; i++) {
        if (textareas[i].name) {
            data[textareas[i].name] = textareas[i].value;
        }
    }
    
    return data;
}

// ============================================
// AUTHENTICATION FUNCTIONS
// ============================================

// Register new user
function register() {
    var data = getFormData('registerForm');
    
    // Check passwords match
    if (data.password !== data.confirm_password) {
        alert('Passwords do not match!');
        return false;
    }
    
    // Call API
    callAPI('register', data).then(function(result) {
        if (result.error) {
            alert('Error: ' + result.error);
        } else {
            alert('Registration successful! Please login.');
            window.location.href = 'login.html';
        }
    });
    
    return false; // Prevent form submission
}

// Login user
function login() {
    var data = getFormData('loginForm');
    
    // Call API
    callAPI('login', data).then(function(result) {
        if (result.error) {
            alert('Error: ' + result.error);
        } else {
            // Save user info in localStorage
            localStorage.setItem('user_id', result.user.id);
            localStorage.setItem('user_name', result.user.first_name);
            localStorage.setItem('farm_name', result.user.farm_name);
            
            alert('Login successful!');
            window.location.href = 'dashboard.html';
        }
    });
    
    return false;
}

// Logout
function logout() {
    localStorage.clear();
    alert('You have been logged out');
    window.location.href = 'index.html';
}

// Check if user is logged in
function checkLogin() {
    var userId = localStorage.getItem('user_id');
    if (!userId) {
        alert('Please login first');
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

// ============================================
// DASHBOARD FUNCTIONS
// ============================================

function loadDashboard() {
    if (!checkLogin()) return;
    
    var userId = localStorage.getItem('user_id');
    var userName = localStorage.getItem('user_name');
    
    // Show welcome message
    var welcomeEl = document.getElementById('welcomeMessage');
    if (welcomeEl) {
        welcomeEl.textContent = 'Welcome back, ' + userName + '!';
    }
    
    // Load statistics
    callAPI('dashboard', {user_id: userId}).then(function(stats) {
        if (stats.error) {
            console.error(stats.error);
            return;
        }
        
        // Update stat cards
        document.getElementById('totalCrops').textContent = stats.total_crops || 0;
        document.getElementById('totalLivestock').textContent = stats.total_livestock || 0;
        document.getElementById('totalSales').textContent = stats.total_sales || 0;
        document.getElementById('totalRevenue').textContent = 'N$' + (stats.total_revenue || 0);
    });
}

// ============================================
// CROP FUNCTIONS
// ============================================

function loadCrops() {
    if (!checkLogin()) return;
    
    var userId = localStorage.getItem('user_id');
    
    callAPI('get_crops', {user_id: userId}).then(function(crops) {
        if (crops.error) {
            console.error(crops.error);
            return;
        }
        
        var tbody = document.getElementById('cropsTableBody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        if (crops.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6">No crops found. Add your first crop!</td></tr>';
            return;
        }
        
        crops.forEach(function(crop) {
            var row = tbody.insertRow();
            row.innerHTML = 
                '<td>' + crop.crop_name + '</td>' +
                '<td>' + crop.crop_type + '</td>' +
                '<td>' + crop.planting_date + '</td>' +
                '<td>' + crop.planted_area + ' ha</td>' +
                '<td>' + crop.status + '</td>' +
                '<td>N$' + crop.planting_cost + '</td>';
        });
    });
}

function addCrop() {
    if (!checkLogin()) return false;
    
    var data = getFormData('cropForm');
    data.user_id = localStorage.getItem('user_id');
    
    callAPI('add_crop', data).then(function(result) {
        if (result.error) {
            alert('Error: ' + result.error);
        } else {
            alert('Crop added successfully!');
            document.getElementById('cropForm').reset();
            loadCrops(); // Reload the table
        }
    });
    
    return false;
}

// ============================================
// LIVESTOCK FUNCTIONS
// ============================================

function loadLivestock() {
    if (!checkLogin()) return;
    
    var userId = localStorage.getItem('user_id');
    
    callAPI('get_livestock', {user_id: userId}).then(function(livestock) {
        if (livestock.error) {
            console.error(livestock.error);
            return;
        }
        
        var tbody = document.getElementById('livestockTableBody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        if (livestock.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6">No livestock found. Add your first animal!</td></tr>';
            return;
        }
        
        livestock.forEach(function(animal) {
            var row = tbody.insertRow();
            row.innerHTML = 
                '<td>' + animal.animal_type + '</td>' +
                '<td>' + animal.breed + '</td>' +
                '<td>' + animal.quantity + '</td>' +
                '<td>' + animal.weight + ' kg</td>' +
                '<td>' + animal.health_status + '</td>' +
                '<td>N$' + animal.purchase_price + '</td>';
        });
    });
}

function addLivestock() {
    if (!checkLogin()) return false;
    
    var data = getFormData('livestockForm');
    data.user_id = localStorage.getItem('user_id');
    
    callAPI('add_livestock', data).then(function(result) {
        if (result.error) {
            alert('Error: ' + result.error);
        } else {
            alert('Livestock added successfully!');
            document.getElementById('livestockForm').reset();
            loadLivestock();
        }
    });
    
    return false;
}

// ============================================
// SALES FUNCTIONS
// ============================================

function loadSales() {
    if (!checkLogin()) return;
    
    var userId = localStorage.getItem('user_id');
    
    callAPI('get_sales', {user_id: userId}).then(function(sales) {
        if (sales.error) {
            console.error(sales.error);
            return;
        }
        
        var tbody = document.getElementById('salesTableBody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        if (sales.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6">No sales recorded yet.</td></tr>';
            return;
        }
        
        sales.forEach(function(sale) {
            var row = tbody.insertRow();
            row.innerHTML = 
                '<td>' + sale.sale_date + '</td>' +
                '<td>' + sale.sale_type + '</td>' +
                '<td>' + sale.buyer_name + '</td>' +
                '<td>' + sale.quantity + '</td>' +
                '<td>N$' + sale.unit_price + '</td>' +
                '<td>N$' + sale.total_amount + '</td>';
        });
    });
}

function addSale() {
    if (!checkLogin()) return false;
    
    var data = getFormData('saleForm');
    data.user_id = localStorage.getItem('user_id');
    
    callAPI('add_sale', data).then(function(result) {
        if (result.error) {
            alert('Error: ' + result.error);
        } else {
            alert('Sale recorded successfully!');
            document.getElementById('saleForm').reset();
            loadSales();
        }
    });
    
    return false;
}

// ============================================
// INITIALIZATION
// ============================================

// Load data when page loads
window.onload = function() {
    // Check which page we're on and load appropriate data
    if (document.getElementById('cropsTableBody')) {
        loadCrops();
    }
    if (document.getElementById('livestockTableBody')) {
        loadLivestock();
    }
    if (document.getElementById('salesTableBody')) {
        loadSales();
    }
    if (document.getElementById('totalCrops')) {
        loadDashboard();
    }
};

console.log('Farm Tracker app loaded successfully!');

