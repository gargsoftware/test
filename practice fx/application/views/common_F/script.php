  <!--   Core JS Files   -->
  <script src="<?=base_url('/assets/common_assets/js/common_script.js')?>"></script>
  <script src="<?=base_url('/assets/common_assets/js/core/popper.min.js')?>"></script>
  <script src="<?=base_url('/assets/common_assets/js/core/bootstrap.min.js')?>"></script>
  <script src="<?=base_url('/assets/common_assets/js/plugins/perfect-scrollbar.min.js')?>"></script>
  <script src="<?=base_url('/assets/common_assets/js/plugins/smooth-scrollbar.min.js')?>"></script>
  <script src="<?=base_url('/assets/common_assets/js/plugins/chartjs.min.js')?>"></script>
  
  
  
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  
  
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js" integrity="sha512-TToQDr91fBeG4RE5RjMl/tqNAo35hSRR4cbIFasiV2AAMQ6yKXXYhdSdEpUcRE6bqsTiB+FPLPls4ZAFMoK5WA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
  
  <script type="text/javascript">
      function showpop(msg, title) {
          toastr.options = {
              "closeButton": false,
              "debug": false,
              "newestOnTop": false,
              "progressBar": true,
              "positionClass": "toast-top-left",
              "preventDuplicates": true,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "120000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
            toastr.success(msg, title);
            toastr.warning(msg, title);
            toastr.info(msg, title);
            toastr.error(msg, title);
            return false;
    }
</script>
<script src="<?=base_url('/assets/common_assets/js/jquery-ui.min.js')?>"></script>
<script src="<?=base_url('/assets/common_assets/js/jquery.auto_complete.min.js')?>"></script>