<?php 
session_start(); require 'config.php';
if($_SESSION['role'] !== 'admin') header('Location: login.php');

$users = mysqli_query($conn, "SELECT u.*, s.s_name, c.city_name FROM students u LEFT JOIN state s ON u.state_id = s.s_id LEFT JOIN city c ON u.city_id = c.c_id");

include 'header.php'; include 'sidebar.php'; 
?>
<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">

    <!-- Smooth Success Alert -->
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'userDeleted'): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="font-medium text-sm">User has been deleted successfully!</span>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">User Management</h1>
            <p class="text-sm text-slate-500 mt-1">View, add, or manage student accounts.</p>
        </div>
        <button onclick="openModal('userModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i>
            Add Student
        </button>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Profile</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    // Suppressing error for UI preview
                    if(isset($users)): 
                        while($row = @mysqli_fetch_assoc($users)): 
                    ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                    <?= strtoupper(substr($row['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900 text-sm"><?= $row['full_name'] ?></div>
                                    <div class="text-xs text-slate-500 mt-0.5"><?= $row['email'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-700"><?= $row['city_name'] ?></div>
                            <div class="text-xs text-slate-400"><?= $row['s_name'] ?></div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Edit Button (Subtle Hover) -->
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors tooltip" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Delete Button (Subtle Hover) -->
                                <a href="delete_user.php?id=<?= $row['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete User">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    endif; 
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Subtle Pagination/Footer Area (Optional, but looks good for SaaS) -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-sm text-slate-500">
            <span>Showing recent students</span>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="userModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            
            <!-- Modal Card -->
            <div class="bg-white w-full max-w-xl rounded-2xl shadow-soft border border-slate-200 overflow-hidden text-left relative transform transition-all sm:my-8">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-bold text-slate-900">Register New Student</h3>
                    <button onclick="closeModal('userModal')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Modal Body (Form) -->
                <form action="save_user.php" method="POST" class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
                            <input type="text" name="full_name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900" placeholder="e.g. John Doe" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                            <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900" placeholder="john@example.com" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900" placeholder="••••••••" required>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Gender</label>
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer relative">
                                    <input type="radio" name="gender" value="male" class="peer sr-only" checked>
                                    <div class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 hover:bg-slate-50 transition-all">
                                        Male
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer relative">
                                    <input type="radio" name="gender" value="female" class="peer sr-only">
                                    <div class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 hover:bg-slate-50 transition-all">
                                        Female
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">State</label>
                            <select name="state_id" id="state_select" onchange="loadCities(this.value)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-700" required>
                                <option value="">Select State...</option>
                                <?php 
                                if(isset($conn)):
                                    $states = @mysqli_query($conn, "SELECT * FROM state");
                                    if($states) {
                                        while($s = mysqli_fetch_assoc($states)) echo "<option value='{$s['s_id']}'>{$s['s_name']}</option>";
                                    }
                                endif;
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">City</label>
                            <select name="city_id" id="city_select" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-700" required>
                                <option value="">Select City...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-8 pt-5 border-t border-slate-100 flex gap-3 justify-end">
                        <button type="button" onclick="closeModal('userModal')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                            Create Account
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

<script>
function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            // Slight delay to allow display:block to apply before animating opacity
            setTimeout(() => {
                modal.firstElementChild.classList.add('opacity-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
        }
function loadCities(stateId) {
    const citySelect = document.getElementById('city_select');
    citySelect.innerHTML = '<option value="">Loading...</option>';
    
    if(!stateId) {
        citySelect.innerHTML = '<option value="">Select City</option>';
        return;
    }

    fetch('get_cities.php?state_id=' + stateId)
        .then(response => response.json())
        .then(data => {
            citySelect.innerHTML = '<option value="">Select City</option>';
            data.forEach(city => {
                citySelect.innerHTML += `<option value="${city.c_id}">${city.city_name}</option>`;
            });
        });
}
</script>
</body>
</html>