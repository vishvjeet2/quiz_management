<?php 
session_start(); 
require 'config.php';

// Security: Only Admin can access
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch messages (Assuming you have a 'contact_us' table)
$query = "SELECT * FROM contact_us ORDER BY id DESC";
$messages = mysqli_query($conn, $query);

include 'header.php'; 
include 'sidebar.php'; 
?>

<main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">

    <!-- Smooth Success Alert (Optional, added for consistency if you use it later) -->
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'msgDeleted'): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="font-medium text-sm">Message deleted successfully.</span>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Support Inbox</h1>
            <p class="text-sm text-slate-500 mt-1">Manage and respond to student inquiries and feedback.</p>
        </div>
        <!-- Placeholder button for SaaS balance -->
        <button class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-slate-900 px-4 py-2.5 rounded-xl font-medium transition-all shadow-sm flex items-center gap-2 text-sm">
            <i class="fas fa-check-double text-slate-400"></i>
            Mark all read
        </button>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-1/4">Sender Details</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-2/4">Subject</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-1/4">Received</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    // Suppressing errors for UI preview
                    if(isset($messages) && @mysqli_num_rows($messages) > 0): 
                        while($msg = @mysqli_fetch_assoc($messages)): 
                    ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group cursor-pointer" onclick="viewMessage('<?= htmlspecialchars(addslashes($msg['name'])) ?>', '<?= htmlspecialchars(addslashes($msg['email'])) ?>', '<?= htmlspecialchars(addslashes($msg['subject'])) ?>', '<?= htmlspecialchars(addslashes($msg['message'])) ?>', '<?= date('d M Y, h:i A', strtotime($msg['created_at'])) ?>')">
                        
                        <!-- Sender Column -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    <?= strtoupper(substr($msg['name'], 0, 1)) ?>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-900 text-sm truncate"><?= htmlspecialchars($msg['name']) ?></div>
                                    <div class="text-xs text-slate-500 mt-0.5 truncate"><?= htmlspecialchars($msg['email']) ?></div>
                                </div>
                            </div>
                        </td>

                        <!-- Subject Column -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-envelope text-slate-300 text-xs"></i>
                                <span class="text-sm font-medium text-slate-700 truncate max-w-xs md:max-w-md">
                                    <?= htmlspecialchars($msg['subject']) ?>
                                </span>
                            </div>
                        </td>

                        <!-- Date Column -->
                        <td class="px-6 py-4">
                            <span class="text-slate-700 text-sm font-medium">
                                <?= date('d M Y', strtotime($msg['created_at'])) ?>
                            </span>
                            <div class="text-xs text-slate-400 mt-0.5">
                                <?= date('h:i A', strtotime($msg['created_at'])) ?>
                            </div>
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Read Button (Clicking row also opens it, but button is good UX) -->
                                <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors tooltip" title="Read Message">
                                    <i class="fas fa-envelope-open-text"></i>
                                </button>
                                <!-- Delete Button -->
                                <a href="delete_msg.php?id=<?= $msg['id'] ?>" onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this message?');" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Delete Message">
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
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                    <i class="fas fa-inbox text-2xl text-indigo-300"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 mb-1">You're all caught up!</h3>
                                <p class="text-sm text-slate-500 max-w-sm mx-auto">There are no new support messages in your inbox at the moment.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Read Message Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-soft border border-slate-200 overflow-hidden text-left relative transform transition-all sm:my-8">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-start bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm flex-shrink-0" id="modalAvatar">
                            <!-- Avatar injected via JS -->
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 leading-tight" id="modalSenderName">Sender Name</h3>
                            <p class="text-xs text-slate-500 mt-0.5" id="modalSenderEmail">sender@email.com</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-medium text-slate-400 hidden sm:block" id="modalDate">Date</span>
                        <button type="button" onclick="closeModal('viewModal')" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 bg-slate-50/30">
                    <div class="mb-4">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Subject</h4>
                        <p class="text-sm font-semibold text-slate-900" id="modalSubject">Message Subject Here</p>
                    </div>
                    
                    <div class="mb-2">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Message</h4>
                        <!-- The message block is styled to look like a clean document/email body -->
                        <div class="bg-white border border-slate-200 rounded-xl p-5 text-sm text-slate-700 leading-relaxed whitespace-pre-wrap shadow-sm" id="modalBody">
                            <!-- Message injected via JS -->
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-end gap-3">
                    <button onclick="closeModal('viewModal')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm">
                        Close
                    </button>
                    <!-- Dummy reply button for UI completeness -->
                    <button onclick="closeModal('viewModal')" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="fas fa-reply text-xs"></i>
                        Reply
                    </button>
                </div>

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
        }

        // Upgraded viewMessage function to handle all the new UI data
        function viewMessage(name, email, subject, body, date) {
            document.getElementById('modalAvatar').innerText = name.charAt(0).toUpperCase();
            document.getElementById('modalSenderName').innerText = name;
            document.getElementById('modalSenderEmail').innerText = email;
            document.getElementById('modalSubject').innerText = subject;
            document.getElementById('modalBody').innerText = body;
            document.getElementById('modalDate').innerText = date;
            
            openModal('viewModal');
        }
    </script>

</body>
</html>