            </main>

            <footer class="admin-footer">
                <p>&copy; 2024 Tourism Management System. All rights reserved.</p>
            </footer>
            </div>
            </div>

            <script src="<?= BASE_URL ?>assets/js/admin.js"></script>
            
            <!-- Mobile Menu Script -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuToggle = document.getElementById('mobileMenuToggle');
                const sidebar = document.getElementById('sidebar');
                const mobileOverlay = document.getElementById('mobileOverlay');
                const sidebarClose = document.getElementById('sidebarClose');
                
                // Toggle mobile menu
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.add('active');
                    mobileOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
                
                // Close mobile menu
                function closeMobileMenu() {
                    sidebar.classList.remove('active');
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                sidebarClose.addEventListener('click', closeMobileMenu);
                mobileOverlay.addEventListener('click', closeMobileMenu);
                
                // Close menu when clicking on a link (mobile only)
                if (window.innerWidth <= 768) {
                    const sidebarLinks = sidebar.querySelectorAll('a');
                    sidebarLinks.forEach(link => {
                        link.addEventListener('click', closeMobileMenu);
                    });
                }
                
                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        closeMobileMenu();
                    }
                });
            });
            </script>
            </body>

            </html>
