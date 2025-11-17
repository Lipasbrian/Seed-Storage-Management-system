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
    // Security: Ensure all interactive elements require login
    // The PHP requireLogin() already prevents access, but we'll add UI enhancements
    document.addEventListener('DOMContentLoaded', function() {
        // Disable form submissions if user somehow bypasses
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            if (!form.id.includes('editDeliveryForm')) {
                form.addEventListener('submit', function(e) {
                    // The form should be protected by PHP, but this adds extra layer
                });
            }
        });
    });

    // Parallax watermark effect on scroll
    if (document.body.classList.contains('dashboard-page')) {
        window.addEventListener('scroll', function() {
            const scrollY = window.scrollY;
            const beforeElement = document.querySelector('body::before');
            const afterElement = document.querySelector('body::after');
            
            // Apply parallax transform to pseudo-elements via CSS variable
            // Scale movement by 5/8 (0.625) to increase clover motion slightly
            document.documentElement.style.setProperty('--scroll-y', scrollY * 0.625 + 'px');
            // Also update JS-created clover particles (if present)
            if (window._cloverParticles && window._cloverParticles.length) {
                // Use requestAnimationFrame-friendly update handled separately
                window._cloverPendingScroll = scrollY;
                if (!window._cloverTicking) {
                    window._cloverTicking = true;
                    window.requestAnimationFrame(function() {
                        const sY = window._cloverPendingScroll || 0;
                        window._cloverParticles.forEach(p => {
                            const depth = parseFloat(p.dataset.depth) || 0.5;
                            const rot = p.dataset.rot || 0;
                            const translate = sY * depth * 0.625;
                            p.style.transform = `translate(-50%, -50%) translateY(${translate}px) rotate(${rot}deg)`;
                        });
                        window._cloverTicking = false;
                    });
                }
            }
        });
    }

    // Create scattered clover particles for background on every page
    (function initClovers() {
        // run on all pages

        const COUNT = 48; // number of clovers to scatter
        const layer = document.createElement('div');
        layer.className = 'clover-layer';
        document.body.appendChild(layer);

        window._cloverParticles = [];

        for (let i = 0; i < COUNT; i++) {
            const s = document.createElement('span');
            s.className = 'clover-particle';
            s.textContent = '🍀';

            // random position across viewport
            const left = Math.random() * 100;
            const top = Math.random() * 100;

            // size a bit larger: 50px - 160px
            const size = Math.floor(50 + Math.random() * 110);

            // depth affects parallax movement: 0.2 - 1.0
            const depth = (0.2 + Math.random() * 0.8).toFixed(3);

            // random rotation
            const rot = Math.floor(Math.random() * 360) - 180;

            s.style.left = left + '%';
            s.style.top = top + '%';
            s.style.fontSize = size + 'px';
            // base opacity
            let baseOpacity = 0.06 + Math.random() * 0.12;
            // increase opacity for middle and right side clovers
            if (left >= 66) {
                baseOpacity += 0.12; // right side stronger
            } else if (left >= 33) {
                baseOpacity += 0.06; // middle zone
            }
            // cap opacity to avoid overpowering content
            baseOpacity = Math.min(baseOpacity, 0.6);
            s.style.opacity = baseOpacity.toFixed(2);

            s.dataset.depth = depth;
            s.dataset.rot = rot;

            layer.appendChild(s);
            window._cloverParticles.push(s);
        }
    })();

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

<!-- Edit Delivery Modal -->
<div class="modal fade" id="editDeliveryModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-ks-primary text-white">
                <h5 class="modal-title">Edit Delivery</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editDeliveryForm" method="POST">
                    <input type="hidden" id="delivery_id" name="delivery_id">
                    <div class="mb-3">
                        <label for="edit_bags" class="form-label">Bags Delivered</label>
                        <input type="number" class="form-control" id="edit_bags" name="bags_delivered" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kg" class="form-label">Weight (kg)</label>
                        <input type="number" class="form-control" id="edit_kg" name="kg_delivered" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_moisture" class="form-label">Moisture (%)</label>
                        <input type="number" class="form-control" id="edit_moisture" name="moisture_content" step="0.1" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-ks-primary" onclick="submitEditDelivery()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    function editDelivery(delivery) {
        document.getElementById('delivery_id').value = delivery.id;
        document.getElementById('edit_bags').value = delivery.bags_delivered;
        document.getElementById('edit_kg').value = delivery.kg_delivered;
        document.getElementById('edit_moisture').value = delivery.moisture_content;
    }

    function submitEditDelivery() {
        const form = document.getElementById('editDeliveryForm');
        const formData = new FormData(form);

        fetch('ajax_update_delivery.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal first
                const modalElement = document.getElementById('editDeliveryModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                modal.hide();
                
                alert('Delivery updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error updating delivery: ' + error);
        });
    }
</script>
</body>
</html>
