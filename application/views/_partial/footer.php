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
 </body>

 </html>