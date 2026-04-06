<?php 
session_start(); require 'config.php';
$pages = mysqli_query($conn, "SELECT * FROM pages");
include 'header.php'; include 'sidebar.php'; 
?>
<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">

    <!-- Smooth Success Alert -->
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'PageDeleted'): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="font-medium text-sm">Page has been deleted successfully!</span>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">CMS Pages</h1>
            <p class="text-sm text-slate-500 mt-1">Manage static pages and content for your website.</p>
        </div>
        <button onclick="openModal('pageModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i>
            New Page
        </button>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Page Title</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">URL Slug</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    // Suppressing errors for UI preview
                    if(isset($pages) && @mysqli_num_rows($pages) > 0):
                        while($p = @mysqli_fetch_assoc($pages)): 
                    ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        
                        <!-- Title Column -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-colors">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <span class="font-semibold text-slate-900 text-sm"><?= htmlspecialchars($p['title']) ?></span>
                            </div>
                        </td>

                        <!-- Slug Column -->
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 text-slate-500 text-xs font-medium border border-slate-200/60 font-mono">
                                <i class="fas fa-link text-[10px] opacity-50"></i>
                                /<?= htmlspecialchars($p['slug']) ?>
                            </div>
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="view_page.php?id=<?= $p['id'] ?>" target="_blank" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors" title="View Page">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="delete_page.php?id=<?= $p['id'] ?>" onclick="return confirm('Are you sure you want to delete this page?');" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Delete Page">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <!-- Empty State -->
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                                    <i class="fas fa-pager text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 mb-1">No pages created</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">Create your first page (like About Us or Terms) to display on the frontend.</p>
                                <button onclick="openModal('pageModal')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                                    Create Page
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Page Modal -->
    <div id="pageModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-soft border border-slate-200 overflow-hidden text-left relative transform transition-all sm:my-8">
                
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-lg font-bold text-slate-900">Create Website Page</h3>
                    <button type="button" onclick="closeModal('pageModal')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="save_page.php" method="POST" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        
                        <!-- Title Input -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Page Title</label>
                            <input type="text" name="title" id="page_title" placeholder="e.g. About Our Academy" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900" required>
                        </div>
                        
                        <!-- Auto-generated Slug Input -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex justify-between">
                                URL Slug
                                <span class="text-[10px] font-normal text-slate-400 font-mono">Auto-generated</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-slate-400 font-mono text-sm">/</span>
                                <input type="text" name="slug" id="page_slug" placeholder="about-our-academy" class="w-full pl-7 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed text-sm font-mono focus:outline-none" readonly required>
                            </div>
                        </div>

                        <!-- Status Select -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Visibility Status</label>
                            <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm text-slate-700">
                                <option value="published">🟢 Published (Visible to everyone)</option>
                                <option value="draft">🟡 Draft (Hidden from public)</option>
                                <option value="archived">⚪ Archived</option>
                            </select>
                        </div>
                    </div>

                    <!-- Content Textarea -->
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Page Content</label>
                        <textarea name="content" rows="6" placeholder="Write your page HTML or content here..." class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder-slate-400 text-slate-900 resize-y"></textarea>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="mt-8 pt-5 border-t border-slate-100 flex gap-3 justify-end">
                        <button type="button" onclick="closeModal('pageModal')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <i class="fas fa-paper-plane text-xs"></i>
                            Publish Page
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</main>
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

document.getElementById('page_title').addEventListener('input', function() {
    let title = this.value;
    let slug = title.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '') 
                    .replace(/\s+/g, '-')       
                    .replace(/-+/g, '-');     
    document.getElementById('page_slug').value = slug;
});
</script>
</body>
</html>