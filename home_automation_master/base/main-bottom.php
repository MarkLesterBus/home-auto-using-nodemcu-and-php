</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../public/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../public/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../public/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../public/dist/js/demo.js"></script>
<script type="text/javascript">
    function display(input) {
       if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(event) {
             $('#user_image').attr('src', event.target.result);
          }
          reader.readAsDataURL(input.files[0]);
       }
    }

    $("#demo").change(function() {
       display(this);
    });
  </script>

<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
</body>
</html>
