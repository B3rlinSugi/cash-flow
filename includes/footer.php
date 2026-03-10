            </div><!-- /.container-fluid -->
        </div><!-- /#content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white border-top mt-auto">
            <div class="container my-auto py-2">
                <div class="copyright text-center my-auto text-muted small">
                    <span><?= APP_NAME ?> &copy; <?= date('Y') ?> — Dibuat dengan <i class="fas fa-heart text-danger"></i> menggunakan PHP & MySQL</span>
                </div>
            </div>
        </footer>
    </div><!-- /#content-wrapper -->
</div><!-- /#wrapper -->

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title"><i class="fas fa-sign-out-alt me-2"></i>Konfirmasi Logout</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3">
                <p class="mb-0">Yakin ingin keluar dari sistem?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <a class="btn btn-danger btn-sm" href="/auth/logout.php">Ya, Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/assets/js/main.js"></script>
<?= $extraScript ?? '' ?>
</body>
</html>
