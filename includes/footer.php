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
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const dashboardContainer = document.querySelector('.dashboard-container');

    if (sidebarToggle && sidebar && dashboardContainer) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sidebar.classList.toggle('hide');
            dashboardContainer.classList.toggle('expanded');
        });

        // Close sidebar when a link is clicked on mobile
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('hide');
                    dashboardContainer.classList.add('expanded');
                }
            });
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.add('hide');
                dashboardContainer.classList.add('expanded');
            }
        });
    }

    // Footer collapse on scroll with inactivity timeout
    const footer = document.querySelector('.footer');
    let lastScrollTime = Date.now();
    let scrollTimeout;
    let inactivityTimeout;

    if (footer) {
        window.addEventListener('scroll', function() {
            const currentTime = Date.now();
            const scrollDelta = currentTime - lastScrollTime;
            
            // Show footer on scroll
            footer.classList.remove('collapsed');
            clearTimeout(inactivityTimeout);

            // If scrolling up (body scrollTop decreasing), keep footer visible
            // If scrolling down, hide footer after 2s inactivity
            lastScrollTime = currentTime;

            // Hide footer after 2 seconds of no scrolling
            inactivityTimeout = setTimeout(function() {
                footer.classList.add('collapsed');
            }, 2000);
        });

        // Show footer on mouse move
        document.addEventListener('mousemove', function() {
            footer.classList.remove('collapsed');
            clearTimeout(inactivityTimeout);
            inactivityTimeout = setTimeout(function() {
                footer.classList.add('collapsed');
            }, 2000);
        });
    }
</script>
</body>
</html>
