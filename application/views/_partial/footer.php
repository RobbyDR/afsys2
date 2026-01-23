 </main>


 <script>
     feather.replace();
 </script>
 <script>
     // Set timeout to auto-dismiss the alert after 5 seconds (5000 milliseconds)
     setTimeout(function() {
         let alert = document.querySelector('.alert');
         if (alert) {
             let bsAlert = new bootstrap.Alert(alert);
             bsAlert.close(); // Close the alert
         }
     }, 5000); // Adjust the time as needed
 </script>

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const btn = document.getElementById('toggleSidebar');

         if (!btn) return;

         // restore state
         if (localStorage.getItem('sidebar') === 'collapsed') {
             document.body.classList.add('sidebar-collapsed');
         }

         btn.addEventListener('click', function() {
             const collapsed = document.body.classList.toggle('sidebar-collapsed');
             localStorage.setItem('sidebar', collapsed ? 'collapsed' : 'open');
         });
     });
 </script>

 </body>

 </html>