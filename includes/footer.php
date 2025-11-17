<?php
// includes/footer.php
?>
    </main>
</div>

<footer class="footer mt-5 py-4 bg-ks-light-green text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">
                    <strong>🌾 Kenya Seed Seed Storage System</strong><br>
                    <small>Efficient seed management for optimal harvest</small>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <small>&copy; <?php echo date('Y'); ?> Kenya Seed Company. All rights reserved.</small>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle functionality
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
    });

    // Close sidebar when a link is clicked on mobile
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 768) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>
</body>
</html>
