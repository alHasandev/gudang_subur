</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
  <strong>Copyright &copy; SuburGroup 2021.</strong>
  Money is Everything.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 4.1.3
  </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark text-center">
  <!-- Control sidebar content goes here -->
  <div class="p-3">
    <img src="<?= $user['foto'] ?>" alt="profil" class="img-circle elevation-5 mb-3" style="width: 8rem">
    <h5><?= $user['username'] ?></h5>
    <a href="logout.php">LOGOUT</a>
  </div>
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- Modal Hapus Data-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus !</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="resetForm()">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="deleteBody">Anda Yakin Akan Menghapus <span>Data</span> ?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="resetForm()">Cancel</button>
        <a href="<?= base_url() ?>" class="btn btn-danger" id="deleteLink">Hapus</a>
      </div>
    </div>
  </div>
</div>

<!-- REQUIRED SCRIPTS -->

<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="./plugins/datatables/jquery.dataTables.min.js"></script>
<script src="./plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="./plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="./plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- overlayScrollbars -->
<script src="./plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="./assets/js/adminlte.min.js"></script>
</body>

</html>