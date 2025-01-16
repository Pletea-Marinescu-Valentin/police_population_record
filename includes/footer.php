</main>
<script src="../assets/script.js"></script> <!-- Link to JavaScript file -->
<footer>
    <div class="footer-container">
        <!-- Information Section -->
        <div class="footer-info">
            <p><strong>Police Population Record</strong></p>
            <p>An application for managing citizens, licenses, and crimes.</p>
        </div>

        <!-- Current Date and Time -->
        <div class="footer-date-time">
            <p id="date-time"></p>
        </div>
    </div>
</footer>

<script>
// Display current date and time in the footer
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    document.getElementById('date-time').textContent = now.toLocaleDateString('en-US', options);
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>

</body>
</html>
