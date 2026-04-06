<?php 
session_start(); require 'config.php';
if($_SESSION['role'] !== 'admin') header('Location: login.php');

// Fetching documents with a JOIN to get the student name
$query = "SELECT d.*, s.full_name 
          FROM document d 
          LEFT JOIN students s ON d.user_id = s.user_id 
          ORDER BY d.id DESC";
$docs = mysqli_query($conn, $query);

include 'header.php'; include 'sidebar.php'; 
?>
<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">

    <!-- Smooth Success Alert -->
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'documentDeleted'): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="font-medium text-sm">Document has been deleted successfully!</span>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">User Documentation</h1>
            <p class="text-sm text-slate-500 mt-1">Manage student IDs, certificates, and uploaded files.</p>
        </div>
        <button onclick="openModal('docModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-cloud-upload-alt text-sm"></i>
            Upload Doc
        </button>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <?php 
        // Suppressing errors for UI preview
        if(isset($docs) && @mysqli_num_rows($docs) > 0):
            while($d = @mysqli_fetch_assoc($docs)): 
        ?>
        <!-- Document Card -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between group hover:-translate-y-1 hover:shadow-soft hover:border-indigo-200 transition-all duration-300">
            
            <div class="flex items-start mb-5 gap-3">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-pdf text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-slate-900 truncate" title="<?= htmlspecialchars($d['full_name'] ?? 'Unknown Student') ?>">
                        <?= htmlspecialchars($d['full_name'] ?? 'Unknown Student') ?>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-mono">ID: #<?= str_pad($d['id'], 4, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="flex gap-2 pt-4 border-t border-slate-100">
                <a href="uploads/<?= $d['doc'] ?>" target="_blank" class="flex-1 flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-700 py-2 rounded-xl text-sm font-semibold hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all">
                    <i class="fas fa-external-link-alt text-xs"></i>
                    View
                </a>
                <a href="delete_doc.php?id=<?= $d['id'] ?>" onclick="return confirm('Delete this document?')" class="w-10 flex items-center justify-center bg-white border border-slate-200 text-slate-400 py-2 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all" title="Delete Document">
                    <i class="fas fa-trash-alt text-sm"></i>
                </a>
            </div>
        </div>
        <?php 
            endwhile; 
        else:
        ?>
        <!-- Empty State -->
        <div class="col-span-full bg-white rounded-2xl border border-slate-200 border-dashed p-12 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                <i class="fas fa-folder-open text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">No documents uploaded</h3>
            <p class="text-sm text-slate-500 max-w-sm mb-6">Upload student certificates, ID cards, or assignment files here.</p>
            <button onclick="openModal('docModal')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-lg transition-colors">
                Upload Document Now
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Upload Document Modal -->
    <div id="docModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            
            <div class="bg-white w-full max-w-md rounded-2xl shadow-soft border border-slate-200 overflow-hidden text-left relative transform transition-all sm:my-8">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-bold text-slate-900">Upload Documentation</h3>
                    <button type="button" onclick="closeModal('docModal')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="upload_logic.php" method="POST" enctype="multipart/form-data" class="p-6">
                    
                    <!-- Student Select -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Assign to Student</label>
                        <select name="user_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-700" required>
                            <option value="">Search & Select a Student...</option>
                            <?php 
                            if(isset($conn)) {
                                $res = @mysqli_query($conn, "SELECT user_id, full_name FROM students WHERE role='user'");
                                if($res) {
                                    while($s = mysqli_fetch_assoc($res)) {
                                        echo "<option value='{$s['user_id']}'>{$s['full_name']}</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- File Input Box -->
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Select Document</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-xl hover:border-indigo-400 hover:bg-indigo-50/50 transition-colors group cursor-pointer" onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 group-hover:text-indigo-500 mb-3 transition-colors"></i>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Click to browse</span>
                                        <input id="file-upload" name="doc" type="file" class="sr-only" required onchange="updateFileName(this)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-[11px] text-slate-400">PDF, PNG, JPG up to 10MB</p>
                            </div>
                        </div>
                        <!-- Little text helper to show chosen file -->
                        <div id="file-name-display" class="hidden mt-2 text-sm text-indigo-600 font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-file-check"></i> <span id="file-name-text"></span>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="mt-8 pt-5 border-t border-slate-100 flex gap-3 justify-end">
                        <button type="button" onclick="closeModal('docModal')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                            Upload File
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
        }
        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            // Reset file input UI on close
            document.getElementById('file-upload').value = '';
            document.getElementById('file-name-display').classList.add('hidden');
        }

        // Small JS to show the file name once selected in our custom dashed box
        function updateFileName(input) {
            const displayDiv = document.getElementById('file-name-display');
            const displayText = document.getElementById('file-name-text');
            
            if (input.files && input.files.length > 0) {
                displayText.textContent = input.files[0].name;
                displayDiv.classList.remove('hidden');
            } else {
                displayDiv.classList.add('hidden');
            }
        }
    </script>
</body>
</html>