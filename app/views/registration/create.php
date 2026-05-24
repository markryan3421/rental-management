<?php 
require_once BASE_PATH . '/app/core/Flash.php';
ob_start(); 
?>

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Create an Account</h2>
                    <form action="?controller=registration&action=store" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile Number (+63)</label>
                            <div class="input-group">
                                <span class="input-group-text">+63</span>
                                <input type="tel" name="mobile_suffix" class="form-control" placeholder="9123456789" maxlength="10" pattern="9[0-9]{9}" required>
                            </div>
                            <small class="text-muted">Enter the 10‑digit number starting with 9 (e.g., 9123456789).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Barangay</label>
                            <select name="barangay" class="form-select" required>
                                <option value="">Select Barangay</option>
                                <option value="Barangay I (Poblacion, Embarcadero, Yap Quiña Subdivision)">Barangay I (Poblacion, Embarcadero, Yap Quiña Subdivision)</option>
                                <option value="Barangay II (Poblacion, Ubos Tindahan, Tamburong)">Barangay II (Poblacion, Ubos Tindahan, Tamburong)</option>
                                <option value="Barangay III (Poblacion, Ubos Simbahan, Sitio Bat-us)">Barangay III (Poblacion, Ubos Simbahan, Sitio Bat-us)</option>
                                <option value="Barangay IV (Poblacion)">Barangay IV (Poblacion)</option>
                                <option value="Barangay V (Poblacion, Ditching Subdivision)">Barangay V (Poblacion, Ditching Subdivision)</option>
                                <option value="Barangay VI (Estrella Village, Salvacion)">Barangay VI (Estrella Village, Salvacion)</option>
                                <option value="Barangay VI-A (Boulevard, Villa Miranda, Sitio Cubay, Pasil)">Barangay VI-A (Boulevard, Villa Miranda, Sitio Cubay, Pasil)</option>
                                <option value="Barangay VII (Poblacion, Malinong, Bandung, Dream Village)">Barangay VII (Poblacion, Malinong, Bandung, Dream Village)</option>
                                <option value="Barangay VIII (Old Simboryo)">Barangay VIII (Old Simboryo)</option>
                                <option value="Barangay IX (Daan Banwa)">Barangay IX (Daan Banwa)</option>
                                <option value="Barangay X (Estado)">Barangay X (Estado)</option>
                                <option value="Barangay XI (Gawahon)">Barangay XI (Gawahon)</option>
                                <option value="Barangay XII (Dacumon)">Barangay XII (Dacumon)</option>
                                <option value="Barangay XIII (Gloryville, Terraville, Villa Victorias – commonly 'Yahweh')">Barangay XIII (Gloryville, Terraville, Villa Victorias – commonly 'Yahweh')</option>
                                <option value="Barangay XIV (Sayding)">Barangay XIV (Sayding)</option>
                                <option value="Barangay XV West Caticlan">Barangay XV West Caticlan</option>
                                <option value="Barangay XV-A East Caticlan">Barangay XV-A East Caticlan</option>
                                <option value="Barangay XVI (Millsite)">Barangay XVI (Millsite)</option>
                                <option value="Barangay XVI-A (New Barrio)">Barangay XVI-A (New Barrio)</option>
                                <option value="Barangay XVII (Garden)">Barangay XVII (Garden)</option>
                                <option value="Barangay XVIII (Palma)">Barangay XVIII (Palma)</option>
                                <option value="Barangay XVIII-A (Golf)">Barangay XVIII-A (Golf)</option>
                                <option value="Barangay XIX (Bacayan)">Barangay XIX (Bacayan)</option>
                                <option value="Barangay XIX-A (Canetown Subdivision)">Barangay XIX-A (Canetown Subdivision)</option>
                                <option value="Barangay XX (Cuaycong)">Barangay XX (Cuaycong)</option>
                                <option value="Barangay XXI (Relocation Site, Takas Patyo)">Barangay XXI (Relocation Site, Takas Patyo)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Street / House No.</label>
                            <input type="text" name="street" class="form-control" placeholder="e.g., 123 Rizal St." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                        <div class="text-center mt-3">
                            Already have an account? <a href="?controller=auth&action=index">Login here</a>
                        </div>
                    </form>
                    <?php foreach (Flash::get('error') as $msg): ?>
                        <div class="alert alert-danger mt-3"><?= htmlspecialchars($msg) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>