<?php 
session_start(); 
require '../config.php';
$user_id = $_SESSION['user_id'];
$my_docs = mysqli_query($conn, "SELECT * FROM document WHERE user_id = $user_id ORDER BY id DESC");

include '../header.php'; include 'student_sidebar.php'; 
?>
<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">My Documents</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your uploaded assignments, IDs, and certificates.</p>
        </div>
        <button onclick="openModal('uploadModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-cloud-upload-alt text-sm"></i>
            New Upload
        </button>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <?php 
        // Suppressing errors for UI preview
        if(isset($my_docs) && @mysqli_num_rows($my_docs) > 0): 
            while($d = @mysqli_fetch_assoc($my_docs)): 
        ?>
        <!-- Document Card -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between group hover:-translate-y-1 hover:shadow-soft hover:border-indigo-200 transition-all duration-300">
            
            <!-- Card Header & Info -->
            <div class="flex items-start gap-3 mb-5">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-slate-900 truncate" title="<?= htmlspecialchars($d['doc']) ?>">
                        <?= htmlspecialchars($d['doc']) ?>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        <?= date('d M Y', strtotime($d['uploaded_at'] ?? 'now')) ?>
                    </p>
                </div>
            </div>

            <!-- Card Action -->
            <div class="pt-4 border-t border-slate-100 mt-auto">
                <a href="../uploads/<?= $d['doc'] ?>" target="_blank" class="w-full flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all">
                    <i class="fas fa-external-link-alt text-xs"></i>
                    Open Document
                </a>
            </div>
            
        </div>
        <?php endwhile; ?>
        
        <?php else: ?>
        <!-- Beautiful Empty State -->
        <div class="col-span-full bg-white rounded-2xl border border-slate-200 border-dashed p-10 md:p-16 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                <i class="fas fa-folder-open text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">No documents found</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">You haven't uploaded any files yet. Upload your assignments, projects, or identity documents here.</p>
            
            <button onclick="openModal('uploadModal')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
                <i class="fas fa-cloud-upload-alt"></i> Upload First File
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Premium Upload Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            
            <div class="bg-white w-full max-w-md rounded-2xl shadow-soft border border-slate-200 overflow-hidden text-left relative transform transition-all sm:my-8">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-bold text-slate-900">Upload Document</h3>
                    <button type="button" onclick="closeModal('uploadModal')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="student_upload_logic.php" method="POST" enctype="multipart/form-data" class="p-6">
                    
                    <!-- Drag & Drop Style File Input -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select File</label>
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
                                <p class="text-[11px] text-slate-400 mt-1">PDF, PNG, or JPG up to 10MB</p>
                            </div>
                        </div>
                        
                        <!-- Dynamic File Name Display -->
                        <div id="file-name-display" class="hidden mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center gap-3">
                            <i class="fas fa-file-check text-indigo-600 text-lg"></i>
                            <span id="file-name-text" class="text-sm text-indigo-700 font-semibold truncate"></span>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="mt-8 pt-5 border-t border-slate-100 flex gap-3 justify-end">
                        <button type="button" onclick="closeModal('uploadModal')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <i class="fas fa-upload text-xs"></i>
                            Upload & Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal & File JS -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            // Reset the file input UI when closing
            document.getElementById('file-upload').value = '';
            document.getElementById('file-name-display').classList.add('hidden');
        }

        // Show the selected file name dynamically
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
</main>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
</body>
</html>